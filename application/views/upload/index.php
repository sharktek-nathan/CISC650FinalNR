<div class="row">
         
    <div class="col-md-5">      
        <form id="submit_file" method="POST" action="<?php echo site_url('/analyze'); ?>">
            <input type="hidden" id="file_location" name="file_location" value=""/>
            <!-- Send Header (Visible by default) -->
            <div>
                <h2>Upload A File</h2>
                <hr>                                                       
            </div>

            <div id="send-fax-div">
                <!-- DropZoneForm -->
                <div class="form-group" >   
                    <label for="exampleInputEmail1">
                        Upload a .pcap and click the button below!
                    </label>
                    <div class="row">
                        <div class="col-xs-4 col-sm-2 col-md-3"><button type="button" class="btn btn-default fileupload-button">Browse Files</button></div>
                        <div class="col-xs-8 col-sm-10 col-md-9"><input id="drag-area" type="email" class="form-control" id="exampleInputEmail1" placeholder="Drag & Drop Files Here" readonly></div>
                    </div>
                    <div class="row">
                        <div id="preview-area" class="dropzone-previews col-xs-12"></div>
                    </div>
                </div>
                
                <!-- Send Button (Visible by default)-->
                <div id="send_button" class="form-group" style="position:relative">
                    <div class="row">
                        <div class="col-xs-7"></div>
                        <div class="col-xs-5">
                            <button type="submit" style="width: 100%;" class="btn btn-primary">Process File</button>
                        </div>
                    </div>
                </div>
                
                <div id="send_step1" class="form-group" style="position:relative">   
                    <label for="exampleInputEmail1">Or view a sample:</label>
                    <ul>
                        <li><?php echo anchor('analyze/sample/shutterstock', 'ShutterStock.pcap', 'title="ShutterStock.pcap"'); ?></li>
                        <li><?php echo anchor('analyze/sample/images_search', 'Google_Images.pcap', 'title="Google_Images.pcap"'); ?></li>
                        <li><?php echo anchor('analyze/sample/request', 'Request.pcap', 'title="Request.pcap"'); ?></li>
                        <li><?php echo anchor('analyze/sample/response', 'Response.pcap', 'title="Response.pcap"'); ?></li>
                        <li><?php echo anchor('analyze/sample/test', 'Test.pcap', 'title="Test.pcap"'); ?></li>
                    </ul>
                </div>
            </div>
        </form>
    </div>    
    
    <!-- 2 col separation -->
    <div class="col-md-2"></div>

    <!-- Recent Faxes -->
    <div class="col-md-5">
        <div>
            <h2>Instructions</h2>
            <hr>                                                       
        </div>
        <div>
            <p>WireShark, TCPDump and various other networking tools generate files while an interface is
capturing packets going over the network. This application serves as a web-based analyzer of these saved captures.</p>
            <p>To see the output of a .pcap file, please upload *one* .pcap file (<55MBs) and click the "Process File" button.</p>
            <p>This application was created by Nathan Rague for CISC 650 (Computer Networks) at NOVA SouthEastern University.
            If you are interested in seeing the source code, you can find it on <a href="https://github.com/sharktek-nathan/pcapviewer">GitHub</a>.</p>
        </div>
    </div>
</div>
<!-- /.row -->


<link type="text/css" href="/css/jquery-ui.css" rel="stylesheet" >
<script type='text/javascript' src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<script type='text/javascript' src="/js/bootstrap.min.js"></script>
<script type='text/javascript' src='/js/dropzone.min.js'></script>
<script type='text/javascript' src='/js/jquery.validate.js'></script>
<script type='text/javascript' src='/js/additional-methods.js'></script>
<script type="text/javascript" src="/js/bootstrap-tokenfield.js" charset="UTF-8"></script>
<link type="text/css" href="/css/bootstrap-tokenfield.css" rel="stylesheet">

<script type='text/javascript' src='/js/jquery.maskedinput.js'></script>
<script type="text/javascript" src="/js/jquery.mask.min.js" charset="UTF-8"></script>

<script type="text/css" src='/css/jquery.dataTables.css'></script>
<script type='text/javascript' src='/js/jquery.dataTables.js'></script>
<script type='text/javascript' src='/js/dataTables.bootstrap.js'></script>

<script type="text/javascript">
    $( document ).ready(function() { 
        
        
        // Handles validation and processing of the New Contact form
        $("#submit_file").submit(function( event ) {
            var file_location = $("#file_location").val();
            if( file_location === null || file_location == "undefined" || file_location == "") {
                event.preventDefault();
                alert("No File was uploaded");
            }
        });
        
        /**
         * When the drag area is hovered over with a file,
         * extend the area, then return it
         */
	$('#drag-area').on('dragover', function(e) {
		$( this ).height( 150 );
	});
	
	$('#drag-area').on('drop mousemove', function(e) {
		$( this ).height( 20 );
	});
	
	$('#drag-area').on('dragleave', function(e) {
		$( this ).height( 20 );
	});
	
        /**
         * Set dropzone settings including the drag area
         * and file upload browse butto n
         */
	var myDropzone = new Dropzone("#drag-area", { 
		maxFiles: 1,
		maxFilesize: 55, //mb
		url: "http://www.pcapviewer.com/upload/save_file", // Set the url
		acceptedFiles: ".pcap",
		thumbnailWidth: 80,
		thumbnailHeight: 80,
		//addRemoveLinks: true, 
		clickable: ".fileupload-button",// Define the element that should be used as click trigger to select files.
		previewsContainer:  "#preview-area",
		createImageThumbnails: true
                //previewTemplate: previewTemplate,
  	});
        
        myDropzone.on('sending', function(file, xhr, formData){
            formData.append('user_ip', '<?php echo $this->input->ip_address() ?>');
        });

        /*
         * This event is triggered when a file is successfully uploaded
         * 
         */
         
        myDropzone.on("success", function(file, response) {
                $('#file_location').val(response);
                console.log(response);
                file.serverId = response;
        });

        myDropzone.on("removedfile", function(file) {
                if (!file.serverId) { return; } // The file hasn't been uploaded
        });

        myDropzone.on("errormultiple", function(files, response) {
          // Gets triggered when there was an error sending the files.
          // Maybe show form again, and notify user of error
        });
        
        

    });
</script>
