<html>
<head>
<script src="conditionize.jquery.js"></script>
<script src="jquery-3.2.1.js"></script>
<script src="tiff.js"></script>
<script src='tesseract.js'></script>

</head>
<body onscroll="scroll()">

<div>
<select id="filelist" size=5>

</select>

Total pages:
<input type="text" id="Total" size=1 />

Current Page :

<button id="cmdFirst"> First </button>
<button id="cmdPrev"> Prev </button>

<input type="text" size=1 id="currentpage" value="1"/> 

<button id="cmdNext"> Next </button>
<button id="cmdLast"> Last </button>

<button id="cmdzoomminus"> ZOOM - </button>
<button id="cmdzoomplus"> ZOOM + </button>
<button id="OCR">OCR</button>

</div>
<div id="imageCanvas">






</div>







</body>
<script>

<?php
$dir = "C:/Users/cyxstudio/Downloads/laragon/www/tiffviewer";
$a = scandir($dir);
?>

var x = <?php echo json_encode($a);  ?>

for (var i = 0 ; i < x.length; i++) {
	
	if (x[i].substr(x[i].lastIndexOf('.') + 1) == "tif"){
		var option = document.createElement("option");
		option.text = x[i];
		document.getElementById('filelist').appendChild(option)
	}
}  //end of for 

var tiff;

document.getElementById("filelist").addEventListener("change", function() {
	var e = document.getElementById("filelist")
	document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].remove();
	tiff = ""
	LoadImage("http://localhost/tiffviewer/" + e.options[e.selectedIndex].text)
	
});

LoadImage("http://localhost/tiffviewer/image.tif")


function LoadImage(image) {

	var xhr = new XMLHttpRequest();
	xhr.responseType = 'arraybuffer';
	xhr.open('GET', image);
	xhr.onload = function (e) {
	
		tiff = new Tiff({buffer: xhr.response});
	   console.log(tiff)
	  
		var canvas = tiff.toCanvas();
		canvas.setAttribute('style', 'width:' + 960 + 'px; height: ' + 540 + 'px; border: ' + 1 + 'px solid blue;');
		setSize(tiff)
  
  
  
  
		  $('#imageCanvas').append(canvas)
		 console.log(canvas)
  
  
  
		 document.getElementById("Total").value = tiff.countDirectory()
		 document.getElementById("currentpage").value = 1
  
  

		  
		document.getElementById("cmdzoomplus").addEventListener("click", function() {

			
		});
		  
		document.getElementById("cmdzoomminus").addEventListener("click", function() {

			
		});



function setSize(tiff) {
	var cWidth = tiff.width()
	var cHeight = tiff.height()
	console.log(cWidth)
	console.log(cHeight)
} //end of setSize



  
};  //end of onload
xhr.send();



}  //end of loadimage


		document.getElementById("cmdNext").addEventListener("click", function() {
			var index = tiff.currentDirectory();
			console.log(index)
			console.log(tiff.countDirectory())
			if (tiff.countDirectory() > 1) {
				index = index + 1
				document.getElementById("currentpage").value = index + 1
				tiff.setDirectory(index)
				canvas = tiff.toCanvas();
				document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].remove();
				$('#imageCanvas').append(canvas)
			}
		});

		document.getElementById("cmdPrev").addEventListener("click", function() {
			var index = tiff.currentDirectory();
			if (index > 0) {
				console.log(index)
				index = index - 1
				console.log(index)
				document.getElementById("currentpage").value = index + 1
				tiff.setDirectory(index)
				canvas = tiff.toCanvas();
				document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].remove();
				$('#imageCanvas').append(canvas)
			}
		});
		  
		document.getElementById("cmdFirst").addEventListener("click", function() {


				document.getElementById("currentpage").value = 1
				tiff.setDirectory(0)
				canvas = tiff.toCanvas();
				document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].remove();
				$('#imageCanvas').append(canvas)
			
		});
		  
		  
		  
		document.getElementById("cmdLast").addEventListener("click", function() {
			tiff.setDirectory(0)
			var index = tiff.countDirectory();
			console.log(index)
			document.getElementById("currentpage").value = index
			tiff.setDirectory(index-1)
			canvas = tiff.toCanvas();
			document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].remove();
			$('#imageCanvas').append(canvas)
			
		});



function scroll() {
	
	document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].style.width = (document.body.scrollTop *10) + "px";
	
	
	
}  //end of scroll

document.getElementById("OCR").addEventListener("click", function() {
	
	var results = "";
	window.Tesseract = Tesseract.create({
                // Path to worker
                workerPath: 'http://localhost/tesseract/worker.js',
                // Path of folder where the language trained data is located
                // note the "/" at the end, this string will be concatenated with the selected language
                langPath: 'http://localhost/tesseract/eng.traineddata/',
                // Path to index script of the tesseract core ! https://github.com/naptha/tesseract.js-core
                corePath: 'http://localhost/tesseract/index.js',
            });
	console.log("OCR begins")			
			Tesseract.recognize(tiff.toCanvas()).then(function(result){
                    // The result object of a text recognition contains detailed data about all the text
                    // recognized in the image, words are grouped by arrays etc
                    console.log(result);
					results = result;

			});
			
	function checkFlag() {
		if(results == "") {
		   window.setTimeout(checkFlag, 1000); /* this checks the flag every 100 milliseconds*/
		} else {
			console.log("OCR ends")	
		  return

		}
	}
	checkFlag();
	
})


</script>



</html>