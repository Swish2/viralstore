# jPreview Plugin
Jquery plugin preview image before upload
# Installation:
- Install via composer: composer require buivannguyen/jpreview
- Add link to jpreview javascript and css file

```
<link rel="stylesheet" type="text/css" href="jpreview.css">
<script src="path/to/jquery.min.js"></script>
<script type="text/javascript" src="path/to/jpreview.js"></script>
  ```
  
# Usage:

```
$(document).ready(function(){
    $('.selector').jPreview();
});
```

And HTML markup:

```
<input type="file" name="test1" class="demo" data-jpreview-container="#demo-1-container">
<div id="demo-1-container" class="jpreview-container"></div>
```
