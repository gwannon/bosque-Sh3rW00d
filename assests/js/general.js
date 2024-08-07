$(document).ready(function () {



  //Index
  var counter = 0;
  let step = 54;
  var currentDiv = 1;
  var indice = (function () {
    var json = null;
    $.ajax({
      'async': false,
      'global': false,
      'url': 'https://bosque.gwannon.com/indice.json',
      'dataType': "json",
      'success': function (data) {
        json = data;
      }
    });
    return json;
  })();

  $("body>section h1 span, body>section h2, body>section h3")
  .not(".noindex")
  .each(function () {
    counter++;    
    if($(this).attr('id')) {
      var pageNumber = 0;
      var currentTitle = this.innerHTML.replace(/(<([^>]+)>)/gi, "");
      $.each(indice, function(i, item) {
        if (currentTitle == item.title) {
          pageNumber = item.page;
          indice[i].title = "";
        }
      });
      $("body>section:nth-of-type(3) div:nth-of-type("+currentDiv+")").append('<a href="#' + $(this).attr('id') + '" class="like' + $(this).prop("tagName") +
      '"><span>'+pageNumber+'</span>' + this.innerHTML.replace(/(<([^>]+)>)/gi, "") + '</a>');
    } else {
      $(this).attr('id', 'anchor' + counter);
      var pageNumber = 0;
      var currentTitle = this.innerHTML.replace(/(<([^>]+)>)/gi, "");
      $.each(indice, function(i, item) {
        if (currentTitle == item.title) {
          pageNumber = item.page;
          indice[i].title = "";
        }
      });
      $("body>section:nth-of-type(3) div:nth-of-type("+currentDiv+")").append('<a href="#anchor' + counter + '" class="like' + $(this).prop("tagName") +
        '"><span>'+pageNumber+'</span>' + this.innerHTML.replace(/(<([^>]+)>)/gi, "") + '</a>');
    }
    currentDiv = Math.floor(counter / step) + 1;
  });

  //WordCount
  var cont = jQuery("body").html();
  cont = cont.replace(/<[^>]*>/g," ");
  cont = cont.replace(/\s+/g, ' ');
  cont = cont.trim();
  var n = cont.split(" ").length
  console.log(n+" words");

  //PageCount
  console.log(($('.saltopagina').length + 1)+" pages");

});