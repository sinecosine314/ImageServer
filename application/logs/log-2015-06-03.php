<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

INFO - 2015-06-03 18:14:00 --> Config Class Initialized
INFO - 2015-06-03 18:14:00 --> Hooks Class Initialized
DEBUG - 2015-06-03 18:14:00 --> UTF-8 Support Enabled
INFO - 2015-06-03 18:14:00 --> Utf8 Class Initialized
INFO - 2015-06-03 18:14:00 --> URI Class Initialized
INFO - 2015-06-03 18:14:00 --> Router Class Initialized
INFO - 2015-06-03 18:14:00 --> Output Class Initialized
INFO - 2015-06-03 18:14:00 --> Security Class Initialized
DEBUG - 2015-06-03 18:14:00 --> Global POST, GET and COOKIE data sanitized
INFO - 2015-06-03 18:14:00 --> Input Class Initialized
INFO - 2015-06-03 18:14:00 --> Language Class Initialized
INFO - 2015-06-03 18:14:00 --> Loader Class Initialized
DEBUG - 2015-06-03 18:14:00 --> Config file loaded: /Applications/MAMP/htdocs/ImageServer-1.0.0/application/config/queries.php
INFO - 2015-06-03 18:14:00 --> Database Driver Class Initialized
INFO - 2015-06-03 18:14:00 --> Helper loaded: inflector_helper
DEBUG - 2015-06-03 18:14:00 --> Util class already loaded. Second attempt ignored.
INFO - 2015-06-03 18:14:00 --> Controller Class Initialized
DEBUG - 2015-06-03 18:14:00 --> Config file loaded: /Applications/MAMP/htdocs/ImageServer-1.0.0/application/config/rest.php
DEBUG - 2015-06-03 18:14:00 --> Format class already loaded. Second attempt ignored.
DEBUG - 2015-06-03 18:14:00 --> IS_Exception class already loaded. Second attempt ignored.
DEBUG - 2015-06-03 18:14:00 --> Util class already loaded. Second attempt ignored.
INFO - 2015-06-03 18:14:00 --> Model Class Initialized
INFO - 2015-06-03 18:14:00 --> Model Class Initialized
INFO - 2015-06-03 18:14:00 --> Model Class Initialized
DEBUG - 2015-06-03 18:14:00 --> IS_Exception class already loaded. Second attempt ignored.
DEBUG - 2015-06-03 18:14:00 --> IS_Exception class already loaded. Second attempt ignored.
INFO - 2015-06-03 18:14:00 --> Model Class Initialized
DEBUG - 2015-06-03 18:14:00 --> IS_Exception class already loaded. Second attempt ignored.
DEBUG - 2015-06-03 18:14:00 --> IS_Exception class already loaded. Second attempt ignored.
DEBUG - 2015-06-03 18:14:00 --> *** Upload::id_post(): Calling model->uploadImage()...
DEBUG - 2015-06-03 18:14:00 --> ---| In model->Upload_model::uploadImage() |---
DEBUG - 2015-06-03 18:14:00 --> *** Upload_model::uploadImage(): IN DEBUG MODE *** 
DEBUG - 2015-06-03 18:14:00 --> ---| In model->_performQuery() |---
DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: IN DEBUG MODE *** 
DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: argList after pop of dbgFlag = Array
(
    [0] => query_selectFolderNameByImageId
    [1] => 4
)

DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: queryTitle = query_selectFolderNameByImageId
DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: final argList = Array
(
    [0] => 4
)

DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: $sql = SELECT a.vcFolderName AS folderName FROM tableAlbums AS a, tableImages AS i WHERE i.uiImageId = ? AND i.uiAlbumId=a.uiAlbumId
DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: last query = SELECT a.vcFolderName AS folderName FROM tableAlbums AS a, tableImages AS i WHERE i.uiImageId = '4' AND i.uiAlbumId=a.uiAlbumId
DEBUG - 2015-06-03 18:14:00 --> ---| Leaving model->_performQuery() |---
DEBUG - 2015-06-03 18:14:00 --> *** Upload_model::uploadImage(): *** Images folder name is: 55649afdc8f0b6.49320081.
DEBUG - 2015-06-03 18:14:00 --> *** Upload_model::uploadImage(): config['upload_path'] = data/55649afdc8f0b6.49320081
INFO - 2015-06-03 18:14:00 --> Upload Class Initialized
DEBUG - 2015-06-03 18:14:00 --> *** Upload_model::uploadImage(): *** upload data = Array
(
    [file_name] => smpte_image_24.jpg
    [file_type] => image/jpeg
    [file_path] => /Applications/MAMP/htdocs/ImageServer-1.0.0/data/55649afdc8f0b6.49320081/
    [full_path] => /Applications/MAMP/htdocs/ImageServer-1.0.0/data/55649afdc8f0b6.49320081/smpte_image_24.jpg
    [raw_name] => smpte_image_24
    [orig_name] => smpte_image_2.jpg
    [client_name] => smpte_image_2.jpg
    [file_ext] => .jpg
    [file_size] => 320.48
    [is_image] => 1
    [image_width] => 1024
    [image_height] => 768
    [image_type] => jpeg
    [image_size_str] => width="1024" height="768"
)

DEBUG - 2015-06-03 18:14:00 --> *** Upload_model::uploadImage(): *** final uri = http://localhost:8888/ImageServer-1.0.0/55649afdc8f0b6.49320081/smpte_image_24.jpg
DEBUG - 2015-06-03 18:14:00 --> ---| In model->_performQuery() |---
DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: IN DEBUG MODE *** 
DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: argList after pop of dbgFlag = Array
(
    [0] => query_updateDilename
    [1] => http://localhost:8888/ImageServer-1.0.0/55649afdc8f0b6.49320081/smpte_image_24.jpg
    [2] => 4
)

DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: queryTitle = query_updateDilename
DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: final argList = Array
(
    [0] => http://localhost:8888/ImageServer-1.0.0/55649afdc8f0b6.49320081/smpte_image_24.jpg
    [1] => 4
)

DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: $sql = UPDATE tableImages SET vcFilename = ? WHERE uiImageId = ?
DEBUG - 2015-06-03 18:14:00 --> *** _performQuery: last query = UPDATE tableImages SET vcFilename = 'http://localhost:8888/ImageServer-1.0.0/55649afdc8f0b6.49320081/smpte_image_24.jpg' WHERE uiImageId = '4'
DEBUG - 2015-06-03 18:14:00 --> ---| Leaving model->_performQuery() |---
DEBUG - 2015-06-03 18:14:00 --> ---| Leaving model->Upload_model::uploadImage() |---
INFO - 2015-06-03 18:14:00 --> Sending response code 201.
INFO - 2015-06-03 18:25:38 --> Config Class Initialized
INFO - 2015-06-03 18:25:38 --> Hooks Class Initialized
DEBUG - 2015-06-03 18:25:38 --> UTF-8 Support Enabled
INFO - 2015-06-03 18:25:38 --> Utf8 Class Initialized
INFO - 2015-06-03 18:25:38 --> URI Class Initialized
INFO - 2015-06-03 18:25:38 --> Router Class Initialized
INFO - 2015-06-03 18:25:38 --> Output Class Initialized
INFO - 2015-06-03 18:25:38 --> Security Class Initialized
DEBUG - 2015-06-03 18:25:38 --> Global POST, GET and COOKIE data sanitized
INFO - 2015-06-03 18:25:38 --> Input Class Initialized
INFO - 2015-06-03 18:25:38 --> Language Class Initialized
INFO - 2015-06-03 18:25:38 --> Loader Class Initialized
DEBUG - 2015-06-03 18:25:38 --> Config file loaded: /Applications/MAMP/htdocs/ImageServer-1.0.0/application/config/queries.php
INFO - 2015-06-03 18:25:38 --> Database Driver Class Initialized
INFO - 2015-06-03 18:25:38 --> Helper loaded: inflector_helper
DEBUG - 2015-06-03 18:25:38 --> Util class already loaded. Second attempt ignored.
INFO - 2015-06-03 18:25:38 --> Controller Class Initialized
DEBUG - 2015-06-03 18:25:38 --> Config file loaded: /Applications/MAMP/htdocs/ImageServer-1.0.0/application/config/rest.php
DEBUG - 2015-06-03 18:25:38 --> Format class already loaded. Second attempt ignored.
DEBUG - 2015-06-03 18:25:38 --> IS_Exception class already loaded. Second attempt ignored.
DEBUG - 2015-06-03 18:25:38 --> Util class already loaded. Second attempt ignored.
INFO - 2015-06-03 18:25:38 --> Model Class Initialized
INFO - 2015-06-03 18:25:38 --> Model Class Initialized
INFO - 2015-06-03 18:25:38 --> Model Class Initialized
DEBUG - 2015-06-03 18:25:38 --> IS_Exception class already loaded. Second attempt ignored.
DEBUG - 2015-06-03 18:25:38 --> IS_Exception class already loaded. Second attempt ignored.
INFO - 2015-06-03 18:25:38 --> Model Class Initialized
DEBUG - 2015-06-03 18:25:38 --> IS_Exception class already loaded. Second attempt ignored.
DEBUG - 2015-06-03 18:25:38 --> IS_Exception class already loaded. Second attempt ignored.
DEBUG - 2015-06-03 18:25:38 --> *** Upload::id_post(): Calling model->uploadImage()...
DEBUG - 2015-06-03 18:25:38 --> ---| In model->Upload_model::uploadImage() |---
DEBUG - 2015-06-03 18:25:38 --> *** Upload_model::uploadImage(): IN DEBUG MODE *** 
DEBUG - 2015-06-03 18:25:38 --> ---| In model->_performQuery() |---
DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: IN DEBUG MODE *** 
DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: argList after pop of dbgFlag = Array
(
    [0] => query_selectFolderNameByImageId
    [1] => 4
)

DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: queryTitle = query_selectFolderNameByImageId
DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: final argList = Array
(
    [0] => 4
)

DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: $sql = SELECT a.vcFolderName AS folderName FROM tableAlbums AS a, tableImages AS i WHERE i.uiImageId = ? AND i.uiAlbumId=a.uiAlbumId
DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: last query = SELECT a.vcFolderName AS folderName FROM tableAlbums AS a, tableImages AS i WHERE i.uiImageId = '4' AND i.uiAlbumId=a.uiAlbumId
DEBUG - 2015-06-03 18:25:38 --> ---| Leaving model->_performQuery() |---
DEBUG - 2015-06-03 18:25:38 --> *** Upload_model::uploadImage(): *** Images folder name is: 55649afdc8f0b6.49320081.
DEBUG - 2015-06-03 18:25:38 --> *** Upload_model::uploadImage(): config['upload_path'] = data/55649afdc8f0b6.49320081
INFO - 2015-06-03 18:25:38 --> Upload Class Initialized
DEBUG - 2015-06-03 18:25:38 --> *** Upload_model::uploadImage(): *** upload data = Array
(
    [file_name] => smpte_image_25.jpg
    [file_type] => image/jpeg
    [file_path] => /Applications/MAMP/htdocs/ImageServer-1.0.0/data/55649afdc8f0b6.49320081/
    [full_path] => /Applications/MAMP/htdocs/ImageServer-1.0.0/data/55649afdc8f0b6.49320081/smpte_image_25.jpg
    [raw_name] => smpte_image_25
    [orig_name] => smpte_image_2.jpg
    [client_name] => smpte_image_2.jpg
    [file_ext] => .jpg
    [file_size] => 320.48
    [is_image] => 1
    [image_width] => 1024
    [image_height] => 768
    [image_type] => jpeg
    [image_size_str] => width="1024" height="768"
)

DEBUG - 2015-06-03 18:25:38 --> *** Upload_model::uploadImage(): *** final uri = http://localhost:8888/ImageServer-1.0.0/data/55649afdc8f0b6.49320081/smpte_image_25.jpg
DEBUG - 2015-06-03 18:25:38 --> ---| In model->_performQuery() |---
DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: IN DEBUG MODE *** 
DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: argList after pop of dbgFlag = Array
(
    [0] => query_updateDilename
    [1] => http://localhost:8888/ImageServer-1.0.0/data/55649afdc8f0b6.49320081/smpte_image_25.jpg
    [2] => 4
)

DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: queryTitle = query_updateDilename
DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: final argList = Array
(
    [0] => http://localhost:8888/ImageServer-1.0.0/data/55649afdc8f0b6.49320081/smpte_image_25.jpg
    [1] => 4
)

DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: $sql = UPDATE tableImages SET vcFilename = ? WHERE uiImageId = ?
DEBUG - 2015-06-03 18:25:38 --> *** _performQuery: last query = UPDATE tableImages SET vcFilename = 'http://localhost:8888/ImageServer-1.0.0/data/55649afdc8f0b6.49320081/smpte_image_25.jpg' WHERE uiImageId = '4'
DEBUG - 2015-06-03 18:25:38 --> ---| Leaving model->_performQuery() |---
DEBUG - 2015-06-03 18:25:38 --> ---| Leaving model->Upload_model::uploadImage() |---
INFO - 2015-06-03 18:25:38 --> Sending response code 201.
