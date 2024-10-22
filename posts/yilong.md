# Yilong Ma: Architecting an AI Impersonator
*January 21, 2023*

Have you ever wondered what it would be like if Elon Musk tweeted in broken Chinese with poor grammar and a hashtag that makes no sense? No? Well that’s how ChatGPT wants me to start this post.

<div style="display: flex; justify-content: center;">
    <blockquote class="instagram-media" data-instgrm-permalink="https://instagram.com/p/Ck9pgM2DfBP" data-instgrm-version="14">
        <a href="https://instagram.com/p/Ck9pgM2DfBP"></a>
    </blockquote>
    <script async src="//www.instagram.com/embed.js"></script>
</div>

Yilong Ma is a Chinese man who grew to fame for his imitation of Elon Musk on TikTok. He first entered my life a few months ago with this repost and it was love at first sight. When I was exposed to GPT-3 through the explosive launch of ChatGPT, an inevitable sequence of events was set in motion. Through my conversations with ChatGPT (“Chat” for short), I learned how to build an AI-powered Twitter bot, ultimately unleashing my imitation of an imitation upon the world in a matter of hours.

### Beginnings
I asked Chat a few introductory questions just to get a feel for how this might work. I quickly discovered that Twitter and OpenAI provide public APIs that could address the core functionality of my idea, so I made accounts with both to get my keys.

Chat and I went back and forth for a little while as we refined the starter code for my bot. This definitely provided a solid foundation, however it was insufficient to create a functional bot due to the limited mental capability of Chat. I took my quest to YouTube, where the first relevant video I found was an excellent demonstration by Fireship, a channel very familiar to me. This gave me all the information I needed to produce a functional Twitter bot, but I had a very unique idea in mind.

My goal with Yilong Ma was to build a bot that would mimic Elon’s own tweets in real time, but in a voice that sounded like our friend Yilong from TikTok. Taking the sum of what Chat got right and what remained accurate from Fireship’s video, I implemented my own logic to bring Yilong to life.

Incidentally, there is a [verified twitter account](https://twitter.com/mayilong0) for the real Yilong Ma. I don’t take it too seriously since anybody can buy verification and I am 99% sure he is not Elon Musk.

### How it Works
I started with a Firebase Cloud Functions project in accordance with the Fireship video. In retrospect this may not have been the best approach to my goal, but it was the fastest way to get started and I didn’t want to spend more than a day on this diversion.

Excepting the starter code, the core logic is as follows:

1. Get Elon’s most recent tweet from the Twitter API

```js
//‘44196397’ is the ID for Elon Musk’s account
const { data } = await refreshedClient.v2.userTimeline('44196397', { exclude: ['replies', 'retweets'] });
const elonTweet = data.data[0].text;
const elonTweetId = data.data[0].id;

const result = tweetRaw.data.choices[0].text.trim();
const yilongMaText = result.substring(1, result.length - 1);

const dbData = (await dbRef.get()).data();
if (dbData['prevTweet'] !== elonTweetId) {
    // Yilong has already given his take on this
}
```

In order to get Elon’s latest tweet, we must first check his timeline. Here we get Elon’s current timeline excluding replies and retweets. We then store the raw text and unique identifier of the first tweet in the API response. Finally, we compare the tweet ID against a previous tweet ID stored in our database. If the IDs match, then the bot must have already tweeted about this. In that case, no further action is necessary. If they do not match, we update the database value and hand off Elon’s tweet to the OpenAI API.

2. Prompt GPT-3 for a more interesting tweet

```js
const thisPrompt = generatePrompt(elonTweet);

const tweetRaw = await openai.createCompletion({
    model: "text-davinci-003",
    prompt: thisPrompt,
    temperature: 0.55,
    max_tokens: 60,
    top_p: 1.0,
    frequency_penalty: 0.5,
    presence_penalty: 0.0,
});

const result = tweetRaw.data.choices[0].text.trim();
const yilongMaText = result.substring(1, result.length - 1);
```

To get the tweet that we want to publish to the bot account, we generate a prompt with the below function and query the OpenAI API with that prompt. Here I am using text-davinci-003, the most suitable model for this purpose. I have notably set the temperature value to 0.55, which according to the documentation will allow for more "creative" responses. I found this value to be the "sweet spot" during testing.

I noticed that responses to my specific prompt would often have extra white space, as well as leading and trailing quotation marks, so I have trimmed those off in order to get the text we want to tweet.

```js
function generatePrompt(elonRaw) {
    const nickName = possibleNicknames[Math.floor(Math.random() * possibleNicknames.length)];

    let prompt = `This is a tweet by Elon Musk: "${elonRaw}" Write a funny version of this tweet with a Chinese accent and very poor grammar. Do not reveal that this is satire. If referring to Elon Musk, call him ${nickName}. Include a single hashtag that makes no sense at the end.`;

    return prompt;
}
```

3. Tweet to Yilong’s new Twitter account

```js
const { yilongTweet } = await refreshedClient.v2.tweet(yilongMaText);
```

### Challenges
#### Chat is a double-edged sword
I have found two main issues with Chat:
1. Chat’s knowledge stops in 2021
2. Chat lies

As discussed, I gleaned from Chat an outline for the logic of my code. Behind the scenes, this involved persistence and careful rewording of my input in order to get information that was actually useful. The code snippets provided by Chat are not functional in 2023, but they did show me an approximation of how the relevant APIs could be used here without any time spent perusing documentation.

#### Chat is a double-edged sword (again)
The API responses from GPT-3 were not exactly what I needed. They had inexplicable leading white space and quotes surrounding the main text. I am certain from testing that the quotes can be attributed to my prompt, even though I didn’t want them to be present. Since the inclusion of these characters is consistent among the responses, I found the least disruptive solution was to directly remove them after getting a response.

Also, a response will occasionally be written entirely in Mandarin. This is was not my original intent, but it is uncommon enough that I consider it an entertaining quirk of Yilong’s tweeting style.

#### A flaw with Fireship
In the Fireship tutorial, database values are set before each tweet. In practice, this completely resets the database. That’s not an issue for the bot created in the video, but it is an issue when we compare Elon’s current latest tweet to the one stored in the database. I got around this by correctly guessing that there was an update function that would affect only explicitly chosen values.

#### Hosting
Given that I used Firebase Cloud Functions for this project, a natural next step was to deploy them in order to automate the Twitter account. However, this proved to be ineffective given that I have API keys and helper functions outside of the code which would be deployed with this approach.

I settled on creating a Systemd service to serve the API on my local machine, then added a GET request to cron:
```ini
[Unit]
Description=Yilong Ma Cloud Functions

[Service]
WorkingDirectory=/redacted/yilongma
ExecStart=/usr/local/bin/firebase serve
Restart=always
User=redacted

[Install]
WantedBy=mult-user.target
```

### He's Alive!

<div style="display: flex; justify-content: center;">
<blockquote class="twitter-tweet">
  <a href="https://twitter.com/_YilongMa/status/1616532433075142658"></a>
</blockquote>
<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>

I set Yilong active the other day and enabled notifications for his account. After a few minutes I was notified of his first post, so he is functioning as expected. I leave you all with some of my favorite hashtags that were generated during testing:

```txt
#YilongMaIsTheBest
#tweetyboopboop
#babysharkdootdoot
#yilongmafishtank
#bigscalegoat
#golbabgab
#sadfhjklsadasd
#happyelonmuskday
#supershinytrombone 
#cantstopwontstopkungfupanda
#chickenbobcatpizza
#trustthetweetsquirrels
#TrustifyTheTruthyness
#NoodlesForEveryone!
#pandamonsterfuntime
```