
      function adapt(link){
        var span = document.getElementsByTagName("span");
         //var allLinks = ul.getElementsByTagName("li");
         for (var i=0; i<span.length-2; i++) {
          span[i].className = "";
         }
         link.className = "active";
      }
    