#!/bin/sh

# Test a user created album creation
curl -i -H "Accept: application/xml" -X PUT \
  --data "title=33333&description=44444" \
  http://localhost:8888/ImageServer-1.0.0/api/albums/id/1
