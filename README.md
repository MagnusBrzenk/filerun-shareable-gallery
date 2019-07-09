# Filerun Shareable Gallery Expansion

## What's This?

This code generates a shareable image/video gallery from a deployed filerun instance. It builds off of filerun's [api-client demo](https://github.com/filerun/api-client).

## Quick Setup

`git clone ...`
`cp .env-template .env` (and fill in variables)
...

## Dev Notes

- When trying to figure out mysql tables schema, I found that the filerun db did NOT export all tables unless I added the option `--single-transaction` as follows: `sudo mysqldump -u root --single-transaction -p filerun > temp.sql`
