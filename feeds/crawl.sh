#!/bin/sh

url=https://feeds.casata.md/feeds.php?l=ro\&action=crawl

wget -qO/dev/null --no-check-certificate $url
