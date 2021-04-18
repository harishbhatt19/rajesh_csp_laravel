<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
   
 
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <title>Map</title>
    <style>
      html, body {
        height: 100%;
        padding: 0;
        margin: 0;
        }
      #map {
       height: 100%;
       width: 100%;
       /*overflow: hidden;*/
       float: left;
       border: thin solid #333;
       }
      
      .zindx{
        z-index: 1;
        position: absolute;
      }
      .canvasjs-chart-credit{
          display:none;
      }
    </style>
  </head>
  <body>
      <div id="map" style="    z-index: -1;"></div>
    <div class="container-fluid"> 
        <div class="row">
            <div class="col-md-3 zindx" style="background: #ffffffa6;
    padding: 1%;">
                <a href="/" class="btn btn-warning">Back To Dashboard</a>
                <button type="button" class="btn btn-primary" id="mphs" style="float:right;margin-bottom: 4%;">Hide chart</button>
                <br>
                <form method="post" action="{{ route('post:get_map_data') }}">
                    @csrf
                    <div class="form-group">
                      
                      <select class="form-control" id="sel1" name="cat">
                        <option>Select Category</option>
                        @foreach($categories as $r)
                        <option value="{{ $r->id }}">{{ $r->category_name }}</option>
                        @endforeach
                      </select>
                      
                    </div>
                    
                    <div class="form-group">
                      
                      <select class="form-control" id="sel2" name="pond">
                        
                        
                      </select>
                      
                    </div>
                    
                    <div class="form-group">
                        
                        <label class="control-label col-md-12" for="email">Select Starting Date:</label>
                          <input type="date" class="form-control" name="dt1" id="dt1" placeholder="select date">
                        
                      </div>
                      
                      <div class="form-group">
                        
                        <label class="control-label col-md-12" for="email">Select Ending Date:</label>
                          <input type="date" class="form-control" name="dt2" id="dt2" placeholder="select date">
                        
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" onclick="" class="btn btn-default">Submit</button>
                        </div>
                      </div>
                  </form>
            </div>
            
            <div class="col-md-9 zindx" style="background: #ffffffa6;
    padding: 1%;margin-left: 25%;">
@if($actull)                
@foreach($actull as $key => $r)                
                <div id="testchart{{ $key }}" class="mapdivss" style="width: 100%; height: 300px;"></div>
@endforeach
@endif     
@if($actull)                
@foreach($actull as $key => $r)
<script>
    var chart = new CanvasJS.Chart("testchart{{ $key }}",
    {
        animationEnabled: true,
        title:{
		text: "Actual vs Projected Salinity"
	},
	axisX:{
		valueFormatString: "DD MMM"
	},
	axisY: {
		title: "Salinity",
		suffix: "",
		stepSize: 0.5,
		minimum: 0
	},
	toolTip:{
		shared:true
	},  
	legend:{
		cursor:"pointer",
		verticalAlign: "bottom",
		horizontalAlign: "left",
		dockInsidePlotArea: true,
	},
        data: [
        {
            type: "line",
            color: "rgba(255,12,32,.3)",
            dataPoints: [
                {{ $r }}
            ]
        },
        {
            type: "column",
            color: "rgba(255,12,32,.3)",
            dataPoints: [
                {{ $forcast[$key] }}
            ]
        }
        ]
    });
chart.render();


</script>
@endforeach
@endif
<script>
    function toogleDataSeries1(e){
	if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	} else{
		e.dataSeries.visible = true;
	}
	e.chart.render();
}
</script>

            </div>
        </div>
    </div> 
    
    <script>
        $(document).ready(function(){
            $('#mphs').click(function(){
                
                if($('.mapdivss').hasClass('maphide')){
                    $('.mapdivss').show().removeClass('maphide');
                    $(this).removeClass('btn-success').addClass('btn-primary').text('Hide chart');
                }else{
                    
                    $('.mapdivss').hide().addClass('maphide');
                    $(this).removeClass('btn-primary').addClass('btn-success').text('Show chart');
                }
            });
          $("#sel1").change(function(){
            var id = $(this).val();
            $.ajax({
                url: "http://tata2.b2cportal.in/api/get_ponds",
                type: "post",
                data: {'category_id':id},
                dataType: 'json',
                beforeSend: function() {
                  
                },
                success: function(data) {
                  if (data.success) {
                      var slct_pond = "<option>Select Pond</option><option value='all'>All Pond</option>";
                      $.each(data.data, function(index, value){
                        //   alert(value.id);
                          slct_pond += "<option value="+value.id+">"+value.pond_name+"</option>";
                        });
                        $("#sel2").html(slct_pond);
                  } else {
                      
                  }
                },
                error: function() {
                
                  return true;
                }
              })
          });
        });
    </script>
    
    <script>
      var map;
      var src = '{{ asset("test1.kml") }}';

      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(-19.257753, 146.823688),
          zoom: 2,
          mapTypeId: 'terrain'
        });

        var kmlLayer = new google.maps.KmlLayer(src, {
          suppressInfoWindows: true,
          preserveViewport: false,
          map: map
        });
        map.addOverlay(kmlLayer);
        kmlLayer.addListener('click', function(event) {
            
          var content = event.featureData.infoWindowHtml;
          alert(content);
          var testimonial = document.getElementById('capture');
          testimonial.innerHTML = content;
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmMlXbGKZfn-86foDw7vUWzftUl4IPtzE&callback=initMap">
    </script>
    
    
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

  </body>
</html>