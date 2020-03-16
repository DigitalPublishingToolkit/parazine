

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>interface test</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>




    <main class="step1">
        <h1>Parazine</h1>
        <p class="intro">Parazine allows you to recontextualize existing academic content. It scrapes the content from an online academic journal of your choice and allows you to create a ‘zine’ from this content. You, the source journal and the tool all provide certain inputs. The resulting publication needs to be printed to be readable. You can make a publication for yourself, but also consider creating it for others (e.g. as a reader).<p><br>
        <select name="target">
          <option value="">Select target website</option>
          <option value="apria">Apria</option>
        </select><br>
        <input id="name1" type="text" name="title" placeholder="Name your Zine"><br>
        <input type="button" class="jscolor" data-jscolor="{onFineChange:'updateColor(this)', width:300, padding:0, shadow:false, borderWidth:0, backgroundColor:'transparent', insetColor:'#000', valueElement:null, styleElement:null}" value="Choose a color" name="color"><br>
        <input type="button" name="go" value="start" onclick="step2()">

    </main>



    <input title="Name Your Zine" class="step2" id="name" type="text" name="title" value="Name your Zine">
    <input title="Choose a Color" class="jscolor step2" id="color" data-jscolor="{onFineChange:'updateColor(this)', width:300, padding:0, shadow:false, borderWidth:0, backgroundColor:'transparent', insetColor:'#000', valueElement:null, styleElement:null}" name="color">
    <input class="step2" id="submit" type="button" name="go" value="print" onclick="submit()">
    <main class="step2">
        <span class="header left">Site index</span>
        <span class="header right">Zine content <pre></pre></span>
      <section id='from' ondragover="dragOver(event)">
          <div id="loader"></div>
      </section>
      <section class="step2" id='to' ondragover="dragOver(event)" >
          <div>Drag articles here<br>
              ...
          </div>
      </section>
    </main>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="jscolor.js"></script>
    <script type="text/javascript" src="script.js"></script>
  </body>
</html>
