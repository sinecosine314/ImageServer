#!/bin/sh

# Test a get of all albums
curl -i -H "Accept: application/xml" -X GET \
  http://localhost:8888/ImageServer-1.0.0/api/albums

# Test a get of a specific album
curl -i -H "Accept: application/xml" -X GET \
  http://localhost:8888/ImageServer-1.0.0/api/albums/id/1
