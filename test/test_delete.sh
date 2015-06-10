curl -i -H "Accept: application/xml" \
   -X PUT --data "title=Title%20For%2012345&description=Description%20For%2012345" \
   http://localhost:8888/ImageServer-1.0.0/api/albums/id/12345

mysql -u root -p --database="ImageServer_db" --execute=\
"INSERT INTO `ImageServer_db`.`tableImages` (`uiImageId`, `vcTitle`, `vcFilename`, `vcDescription`, `uiAlbumId`, `tsModified`) VALUES (NULL, 'Title for image 1', 'test1.jpg', 'Description for image 1', '12345', CURRENT_TIMESTAMP);"

mysql -u root -p --database="ImageServer_db" --execute=\
"INSERT INTO `ImageServer_db`.`tableImages` (`uiImageId`, `vcTitle`, `vcFilename`, `vcDescription`, `uiAlbumId`, `tsModified`) VALUES (NULL, 'Title for image 2', 'test2.jpg', 'Description for image 2', '12345', CURRENT_TIMESTAMP);"
