<html>
<head>
<script src="conditionize.jquery.js"></script>
<script src="jquery-3.2.1.js"></script>
<script src="tiff.js"></script>
<script src='tesseract.js'></script>

</head>
<body onscroll="scroll()">

<div style="display:inline-block;">
<select id="filelist" size=5>

</select>
</div>
<div style="display:inline-block">
<div style="display:block;">
policy<input type="text" id="policy" />
CLM<input type="text" id="clm" />
</div>
<div style="display:block;">
Total pages:
<input type="text" id="Total" size=1 />

Current Page :

<button id="cmdFirst"> First </button>
<button id="cmdPrev"> Prev </button>

<input type="text" size=1 id="currentpage" value="1"/> 

<button id="cmdNext"> Next </button>
<button id="cmdLast"> Last </button>

<button id="OriginalSize"> Original </button>
<button id="FitSize"> Fit </button>
<button id="OCR">OCR</button>
</div>
</div>


<div id="imageCanvas">
</div>


<br>
<br><br>

<div id="bundleimage">
</div>






</body>
<script>

var OCRArray = [];

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
	$("#bundleimage").empty();
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
  
		for (var o = 0 ; o < document.getElementById("Total").value; o++) {
			
			//console.log(o)
			tiff.setDirectory(o)
			canvas = tiff.toCanvas();
			$('#bundleimage').append(canvas)
			
			OCRbegin(o, canvas)
			
			sleep(100)
			
		} 

		 tiff.setDirectory(0)
		 



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
			//console.log(index)
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
				//console.log(index)
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
			//console.log(index)
			document.getElementById("currentpage").value = index
			tiff.setDirectory(index-1)
			canvas = tiff.toCanvas();
			document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].remove();
			$('#imageCanvas').append(canvas)
			
		});
		
		document.getElementById("currentpage").addEventListener("change", function() {
			
			
		});



function scroll() {
	oWidth = document.getElementById('imageCanvas').getElementsByTagName("canvas")[0]
	pWidth = document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].width
	pHeight = document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].height
	
	//console.log(pWidth + (document.body.scrollTop/10))
	//oWidth.style = "width:" + pWidth + (document.body.scrollTop/10) + "px;";
	
	//window.scrollTo(0, 0);
	
	
	
}  //end of scroll

document.getElementById("OCR").addEventListener("click", function() {
	
	console.log(OCRArray);
	
		for (var i =0; i < OCRArray.length; i++) {
			for (var o =0; o < OCRArray[i].lines.length; o++) {
				console.log(OCRArray[i].lines[o].text)
			}
		}

	
})


document.getElementById("OriginalSize").addEventListener("click", function() {
	
	var object = document.getElementById('imageCanvas').getElementsByTagName("canvas")[0]
	pWidth = document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].width
	pHeight = document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].height
	
	object.style = "width:" + pWidth + "px;height:" + pHeight + "px;";
	
})
document.getElementById("FitSize").addEventListener("click", function() {
	
	var object = document.getElementById('imageCanvas').getElementsByTagName("canvas")[0]
	pWidth = document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].width
	pHeight = document.getElementById('imageCanvas').getElementsByTagName("canvas")[0].height
	
	ratio = 1300/pWidth
	
	object.style = "width:1200px;height:" + (pHeight * ratio) + "px;";

	
})



function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

function OCRbegin(iteration, theimage) {
	
			window.Tesseract = Tesseract.create({
                // Path to worker
            workerPath: 'http://localhost/tesseract/worker.js',
                // Path of folder where the language trained data is located
                // note the "/" at the end, this string will be concatenated with the selected language
            langPath: 'http://localhost/tesseract/eng.traineddata/',
                // Path to index script of the tesseract core ! https://github.com/naptha/tesseract.js-core
             corePath: 'http://localhost/tesseract/index.js',
        });
		
					
		Tesseract.recognize(theimage).then(function(result){
                    // The result object of a text recognition contains detailed data about all the text
                    // recognized in the image, words are grouped by arrays etc
					console.log(iteration)
                     console.log(result);
					 OCRArray[iteration] = result;
					

		});
	
}

var md = false
document.getElementById("imageCanvas").addEventListener("mousedown", function() {

	md = true;
    var x = event.clientX;
    var y = event.clientY;	
	window.scrollTo(x, y);	
	document.getElementById("imageCanvas").style.cursor = "grabbing";
});

document.getElementById("imageCanvas").addEventListener("mouseup", function() {
	md = false;
	document.getElementById("imageCanvas").style.cursor = "default";
});
document.getElementById("imageCanvas").addEventListener("mouseout", function() {
	md = false;
	document.getElementById("imageCanvas").style.cursor = "default";
});

document.getElementById("imageCanvas").addEventListener("mousemove", function() {

	if (md == true) {
		var x = event.clientX;
		var y = event.clientY;	
		window.scrollTo(x, y);	
		document.getElementById("imageCanvas").style.cursor = "grabbing";
	}
	
});


</script>



</html>