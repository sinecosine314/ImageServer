#!/bin/sh

# Test a default album creation
#curl -i -H "Accept: application/xml" -X POST \
#  http://localhost:8888/ImageServer-1.0.0/api/albums

# Test a user created album creation
curl -i -H "Accept: application/xml" -X PUT \
  --data "title=New+Title&description=New+description" \
  http://localhost:8888/ImageServer-1.0.0/api/albums/id/100

# Test an error - record 1 should already exist
curl -i -H "Accept: application/xml" -X PUT \
  --data "title=New+Title&description=New+description" \
  http://localhost:8888/ImageServer-1.0.0/api/albums/id/1

#
curl -i -H "Accept: application/xml" -X GET \
  http://localhost:8888/ImageServer-1.0.0/api/albums
