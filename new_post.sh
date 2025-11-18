#!/bin/bash

# new_post.sh - Create a new blog post with frontmatter template
# Usage: ./new_post.sh "My Post Title"

if [ -z "$1" ]; then
    echo "Usage: ./new_post.sh \"Post Title\""
    exit 1
fi

TITLE="$1"
SLUG=$(echo "$TITLE" | tr '[:upper:]' '[:lower:]' | tr ' ' '-' | sed 's/[^a-z0-9-]//g')
DATE=$(date +%Y-%m-%d)
FILENAME="posts/${SLUG}.md"

if [ -f "$FILENAME" ]; then
    echo "Error: $FILENAME already exists!"
    exit 1
fi

cat > "$FILENAME" << EOF
---
title: "$TITLE"
date: $DATE
categories: []
tags: []
excerpt: ""
---

# $TITLE
*$(date +"%B %d, %Y")*

Your content here...
EOF

echo "Created new post: $FILENAME"
echo ""
echo "Next steps:"
echo "1. Edit $FILENAME and add your content"
echo "2. Update categories, tags, and excerpt in the frontmatter"
echo "3. Run: ansible-playbook ansible/update_blog.yml -i ansible/hosts to deploy"