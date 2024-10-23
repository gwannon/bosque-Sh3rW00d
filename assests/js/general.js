$(document).ready(function () {
  var counter = 0;
  let step = 62;
  var currentDiv = 1;

  $("body>section h1, body>section h2")
  .not(".noindex, section:last-of-type *")
  .each(function () {
    var label;
    counter++;    
    $(this).attr('id', 'anchor' + counter);
    var pageNumber = 0;
    if($(this).prop("tagName") == 'H1') {
      label = $(this).children("strong").text(); /*label = this.innerHTML;*/
    } else label = this.innerHTML.replace(/(<([^>]+)>)/gi, "");

    $.each(indice, function(i, item) {
      if (label == item.title) {
        pageNumber = item.page;
        indice[i].title = "";
      }
    });

  
    $("body>section:nth-of-type(3) div:nth-of-type("+currentDiv+")").append('<a href="#anchor' + counter + '" class="like' + $(this).prop("tagName") + '"><span>'+pageNumber+'</span>' + label + '</a>');
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