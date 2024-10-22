# Just Set Up the Printers: Reverse Engineering an Ancient USB Printer
*October 22, 2024*

<div align="center"><img src="/images/blog/printer/justSetUpThePrinters.png" alt="Just Set Up the Printers" style="width:80%"/></div>
In the age of IoT and "smart" everything, finding a printer that just prints without any bloated or invasive software is increasingly difficult. That's why I decided to breathe new life into my old and dumb Canon Pixma MP480 - a reliable, no-nonsense all-in-one printer with USB connectivity and zero smart features. The goal: transform it into a wireless printer that I can use securely within my home network.

## Initial Research and Hardware Selection

My journey began with research into simple embedded solutions to add wireless functionality to a USB printer. I knew not of the complexities of proprietary printer protocols. Initially, I considered using an ESP32 board, known for its WiFi capabilities and low cost. However, deeper investigation led me back to the Arduino Uno R4 WiFi (which I already owned) for several key reasons:

1. Built-in USB host functionality, crucial for communicating with the printer
2. Onboard barrel jack for power, leaving the USB port free for printer connection
3. Integrated WiFi capabilities, eliminating the need for additional modules

We will see why this was a mistake later on!

The hardware list for this project includes:

1. Arduino Uno R4 WiFi (already owned)
2. Canon MP480 Printer (already owned)
3. Canon MP480 Power Cable (already owned)
4. USB B Male to C Female Adapter ($6.88)
5. USB C to C cable ($6.99 for a bundle)
6. 9V2A DC Power Supply ($9.99)

Total additional cost: $35.82 (including 8.25% sales tax and $9.99 shipping)

The USB-B to USB-C adapter proved to be a rare component, not readily available in my current location without a nearby Micro Center, necessitating an online order and a wait for shipping. This was kind of a ripoff, but I considered the project to be a sufficiently worthwhile learning experience.

## Decoding the Printer's Language

The first major breakthrough came from the discovery of the `sane-pixma` project, a SANE (Scanner Access Now Easy) backend for Canon PIXMA devices. This provided valuable insights into the communication protocol used by Canon printers. While this information was helpful, it primarily focused on scanning functionality rather than printing. At the time however, I saw a wealth of information before me after a series of fruitless web searches, as is often the case with such ancient hardware.

To gather more specific data about the printing process, I turned to Wireshark with USBPcap to capture USB traffic during a print job. I began capture, connected the printer, and printed the plain text, `test`. The initial capture yielded 794 packets in just 6 seconds, which was overwhelming but unsurprising give all of my USB peripherals. Through careful application of filters, I narrowed down the relevant data:

1. Applied filter `_ws.col.info == "GET DESCRIPTOR Response DEVICE"` in order to find the device information
    - Revealed the Vendor ID (VID) and Product ID (PID) in the relevant packets
2. From there, applied filter: `usb.idVendor == 0x04a9 && usb.idProduct == 0x1731` (Canon MP480 identifiers)
3. Further filtered to `usb.transfer_type == 3` (bulk transfers, typically used for printer data)
4. Finally, isolated packets with `usb.bInterfaceClass == 0x07` (printer class)

This process reduced the data to the most relevant packets for our analysis, namely our print job. Here's a breakdown of key discoveries:

### Initialization Sequence
```txt
1b 5b 4b 02 00 00 1f 42 4a 4c 53 54 41 52 54 0a
43 6f 6e 74 72 6f 6c 4d 6f 64 65 3d 43 6f 6d 6d
6f 6e 0a 53 65 74 54 69 6d 65 3d 32 30 32 34 31
30 31 37 32 33 31 31 33 30 0a 42 4a 4c 45 4e 44
0a
```
Excepting some setup and command identifiers, this translates to:
```txt
BJLSTART
ControlMode=Common
SetTime=20241017231130
BJLEND
```

### Print Data Transmission
Print data is sent in chunks, each preceded by a header:
```txt
1b 28 46 <2 bytes for length>
```
Where `1b 28 46` is the ASCII for "ESC ( F", followed by a 2-byte length specifier.

### Job End Sequence
```txt
1b 5b 4b 0b 00 00 1e 00 09 53 53 52 3d 44 46 3b
```
ASCII: `ESC [ K <7 bytes> SSR=DF;`

While we can intuit the meaning of this sequence, a full understanding will require further experimentation.

### What We Learned
- The frequent use of 1b (ESC) indicates that many of these sequences are printer control commands
- The printer ostensibly utilizes a combination of ASCII text commands (like BJLSTART) and binary data for control and configuration
- The `0a` (newline) characters are often used to separate different commands or parameters within a sequence
- The consistent structure (ESC followed by command identifiers and parameters) suggests a well-defined command protocol, likely proprietary to Canon printers

This detailed analysis of the USB traffic was crucial in understanding how to effectively communicate with the printer.

## Developing the Minimal Viable Product

With the printer's communication protocol mostly decoded, I developed a minimal example to test basic printing functionality. Here's a breakdown of the key components:

```cpp
#include <USBHost.h>

USBHost usbHost;
USBPrinter printer(usbHost);

void setup() {
  pinMode(LED_BUILTIN, OUTPUT);
  
  // Fast blink - starting up
  for(int i=0; i<3; i++) {
    digitalWrite(LED_BUILTIN, HIGH);
    delay(100);
    digitalWrite(LED_BUILTIN, LOW);
    delay(100);
  }
  
  usbHost.Init();
  
  // Slow blink while waiting for printer
  while (!printer) {
    digitalWrite(LED_BUILTIN, HIGH);
    delay(500);
    digitalWrite(LED_BUILTIN, LOW);
    delay(500);
    usbHost.Task();
  }
  
  // Solid LED - printer connected
  digitalWrite(LED_BUILTIN, HIGH);
  delay(1000);
  digitalWrite(LED_BUILTIN, LOW);

  // Initialize printer
  const char* INIT_SEQUENCE = "\x1B[K\x02\x00\x00\x1FBJLSTART\nControlMode=Common\nSetTime=20241017231130\nBJLEND\n";
  printer.write((uint8_t*)INIT_SEQUENCE, strlen(INIT_SEQUENCE));

  // Start print job
  const char* START_SEQUENCE = "\x1B[K\x02\x00\x00\x1FBJLSTART\nControlMode=Common\nSetTime=20241017231130\nBJLEND\n";
  printer.write((uint8_t*)START_SEQUENCE, strlen(START_SEQUENCE));

  // Send print data
  const char* testData = "Test";
  uint8_t header[] = {0x1B, 0x28, 0x46, strlen(testData) & 0xFF, (strlen(testData) >> 8) & 0xFF};
  printer.write(header, sizeof(header));
  printer.write((uint8_t*)testData, strlen(testData));

  // End print job
  const char* END_SEQUENCE = "\x1B[K\x0B\x00\x00\x1E\x00\x09SSR=DF;";
  printer.write((uint8_t*)END_SEQUENCE, strlen(END_SEQUENCE));

  // When done, rapid blink for success
  for(int i=0; i<5; i++) {
    digitalWrite(LED_BUILTIN, HIGH);
    delay(100);
    digitalWrite(LED_BUILTIN, LOW);
    delay(100);
  }
}

void loop() {
  usbHost.Task();
}
```

This minimal example encompasses several crucial steps:

1. **USB Host Initialization**: The `USBHost` and `USBPrinter` objects are set up to manage the USB communication.

2. **Printer Detection**: The code waits in a loop until the printer is detected, ensuring a stable connection before proceeding.

3. **Printer Initialization**: The INIT_SEQUENCE is sent to prepare the printer for a new session.

4. **Job Start**: The START_SEQUENCE signals the beginning of a new print job.

5. **Data Transmission**: The test data "Test" is sent, preceded by a header specifying the data length.

6. **Job End**: The END_SEQUENCE is sent to finalize the print job.

We use the LED to indicate status since we cannot simultaneously connect the Arduino to a development computer and the printer.

This minimal example should serve as a proof of concept for the USB communication protocol we've developed based on our Wireshark analysis, except...

<div style="text-align:center;font-weight:bold">USBHost.h is incompatible with the RA4M1-powered Uno R4!</div>

<br/><div align="center"><img src="/images/blog/printer/printerFrustration.png" alt="Just Set Up the Printers" style="width:40%"/></div><br/>

Indeed, I have since discovered that there are **no** USB host libraries available for the newest revision of the board. Furthermore, the Arduino USB Host Shield has been discontinued.

## Current Status and Next Steps
The journey so far has involved a deep dive into USB protocols, printer communication methods, and embedded programming. There are a few potential approaches to move forward from here:
1. Purchase an Arduino or similar board with functional USB host support
2. Use something like a Raspberry Pi for a higher-level approach
3. Write a library for the RA4M1-based Arduinos to enable the dormant USB host functionality

For such a trivial project, the pragmatic solution is clearly to use the Raspberry Pi and get this working in an afternoon. That is the approach I will take in order to terminate this project and move on. However, I also took on this task in order to deepen my understanding of embedded programming. To that end, I have decided to pursue Approach #3 as a separate project.

Thus, I plan on writing the first(?) USB host library for RA4M1-based Arduino boards. As the saying goes, I have concepts of a plan.