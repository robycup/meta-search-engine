<!doctype html>
 
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Search Engine</title>
    <link rel="stylesheet" href="lib/css/smoothness/jquery-ui-1.9.1.custom.css" />
    <script src="lib/js/jquery-1.8.2.js"></script>
    <script src="lib/js/jquery-ui-1.9.1.custom.min.js"></script>
    <link rel="stylesheet" href="lib/css/style.css" />
    <script>
    $(function(){
        $("input[type=submit]")
            .button()
            .click(function(event){
                event.preventDefault();
                if ($('#query').val() == ''){
                    alert('Enter a word');
                    return;
                }
                var query = $('#query').val();
                var words = query.split(/\b/);
                $('#loader').css('visibility','visible');
                search(words[0]);
            });
    
        $("#query").keypress(function(event){
           if (event.which == 13) {
                event.preventDefault();
                if ($('#query').val() == ''){
                    alert('Enter a word');
                    return;
                }
                var query = $('#query').val();
                var words = query.split(/\b/);
                $('#loader').css('visibility','visible');
                search(words[0]);
           }
        });


     });  
        
    
    
    
    
    
    $(function() {
         $("#tabs").tabs();
        
    });
    
   
   function search(word){
         var tabContent;
         var url = 'scrap.php?q=' + word;
         var jqxhr = $.getJSON(url,populate);
         jqxhr.error(function(){
             alert("Unable to retrieve datas");
             $('#loader').css('visibility','hidden');
         });
         
         
         function populate(data){
             $('#loader').css('visibility','hidden');
             $.each(data, function(engineName, engineResults){
                 tabContent='';
                 if (engineName == 'global'){
                    $.each(engineResults, function(index, record){
                    tabContent += '<p class="title"><a class="sitelink" href="' + record['url'] + '">' + record["descr"] + '</a><span class="sourceengine">[' + record['engine'] + ']</span></p>';
                    tabContent += '<div class="url">' + record['url'] + '</div>';
                    tabContent += '<p class="abstract">' + record['abstr'] + '</p>'; 
                    }); 
                 }
                 else{
                    $.each(engineResults, function(index, record){
                    tabContent += '<p class="title"><a class="sitelink" href="' + record['url'] + '">' + record["descr"] + '</a></p>';
                    tabContent += '<div class="url">' + record['url'] + '</div>';
                    tabContent += '<p class="abstract">' + record['abstr'] + '</p>';
                    });
                 }
                 $('#' + engineName).html(tabContent);
             });
         }
    }
    
    </script>
</head>
<body>
    <div id="search-box">
      <div id="wrapper">
        <input type="input" name="query" id="query" placeholder="Insert a word" /><input id="search-button" type="submit" value="search">   
        <div id="loader">
            <img src="lib/images/ajax-loader.gif">
        </div>
      </div>  
    </div>
<div id="tabs">
    <ul>
        <li><a href="#global">Global</a></li>
        <li><a href="#google">Google</a></li>
        <li><a href="#yahoo">Yahoo</a></li>
        <li><a href="#bing">Bing</a></li>
    </ul>
    <div id="global">
        <p></p>
    </div>
    <div id="google">
        <p></p>
    </div>
    <div id="yahoo">
        <p></p>
    </div>
    <div id="bing">
        <p></p>
    </div>
     
</div>
 
 
</body>
</html>