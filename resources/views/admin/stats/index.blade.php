@extends('layouts.default')

@section('breadcrumb')
  <ol class="breadcrumb hidden-xs">
    <li><a href="/superadmin/">Super Admin</a></li>
    <li class="active">Stats</li>
  </ol>
@endsection

@section('content')

<div id="chart_div" style="width:100%; height:400px"></div>

@endsection


@section('footer')
   <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

      // Create the data table.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Date');
      data.addColumn('number', 'Order');
      data.addRows([
        @foreach($days as $date => $count)
          ['{{$date}}',{{$count}}],
        @endforeach
      ]);

      // Set chart options
      var options = {'title':'Orders per Day',
                     'width':'100%',
                     'height':400};

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
  </script>
@endsection

