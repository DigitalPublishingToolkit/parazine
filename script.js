var selected;
var name;
var color="#FF00FF";
var cache = true;

function dragOver( e ) {

  if(e.target.id == 'to'){
    $('#to div').hide();
    e.target.appendChild(selected);
    e.preventDefault();
  }else if(e.target.parentNode.id == 'to'){
    if ( isBefore( selected, e.target ) ){
      e.target.parentNode.insertBefore( selected, e.target )
      e.preventDefault();
    }else{
      e.target.parentNode.insertBefore( selected, e.target.nextSibling )
      e.preventDefault();
    }
  }else if(e.target.id == 'from' || e.target.parentNode.id == 'from' || e.target.parentNode.parentNode.id == 'from' ){
    var bron = document.getElementById("details-"+selected.dataset.tag);
    bron.appendChild(selected);
    bron.setAttribute("open","open");
    e.preventDefault();
  }

  var words = 0;
  $('#to p').each(function(){
    words += parseInt($(this)[0].dataset.words);
  })
  $('.header.right pre').html("("+words + " words)");
  $('details:not(:has(p))').addClass('empty').removeAttr("open");
  $('details:has(p)').removeClass('empty');

}

function dragEnd() {
  selected = null;
}

function dragStart( e ) {
  selected = e.target
  e.dataTransfer.dropEffect = "move";
  e.stopPropagation();
}

function isBefore( el1, el2 ) {
  var cur
  if ( el2.parentNode === el1.parentNode ) {
    for ( cur = el1.previousSibling; cur; cur = cur.previousSibling ) {
      if (cur === el2) return true
    }
  } else return false;
}

//submit---------------------------------------------------------------------------
function submit(){
  var artikelen = [];
  name = $( "#name" ).val();

  $('#to p').each(function(){
    artikelen.push($(this).data("url"));
  })

  if(artikelen.length == 0){
    alert('Please select articles first!');
  }else{
    dataToSend = JSON.stringify(artikelen);
    window.open("testbuild.php?name="+encodeURIComponent(name)+"&color="+encodeURIComponent(color)+"&data="+encodeURIComponent(artikelen)+"");
  }


}
//colorpicker---------------------------------------------------------------------------

function updateColor(picker){
  var rgba = "rgba("+picker.rgb.join()+",0.2)";
  var rgb = "rgb("+picker.rgb.join()+")";
  color= picker.toHEXString();

  document.body.style.setProperty('--color',rgb);
  document.body.style.setProperty('--color-transparent',rgba);
}


window.onhashchange = function() {       
    if (location.hash == '#step2') {        
        step2();
    } else {   
        step1();
    }
}

function step1(){
  $( ".step2" ).fadeOut();
  $( ".step1" ).fadeIn();
}

function step2(){
  location.hash = 'step2';

  $( "#loader" ).fadeIn();
  $( "#name" ).attr('value', $( "#name1" ).val());
  $( ".step1" ).fadeOut();
  $( ".step2" ).fadeIn();

  if($( "#name1" ).val()!=''){
    if(cache){
      setTimeout(function(){
        $.get("cached-scrape-clean.html", inladen);
      }, 3000);
    }else{
      $.get("scrape.php?href="+encodeURI(url), inladen);
    }
  }else{
    alert('Please enter a name')
  }
}

function inladen(data){ 
  $( "#loader" ).fadeOut();
  $( "#from" ).html( data );
  var rand = Math.floor(Math.random() * $('details').length);
  $('details:nth-of-type('+rand+')').attr('open','');
}
