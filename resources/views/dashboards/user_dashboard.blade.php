@push('scripts')
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>

    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/locales/de_DE.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/germanyLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/fonts/notosans-sc.js"></script>

    <script src="https://cdn.amcharts.com/lib/5/themes/Responsive.js"></script>
    <script>
        var goalsProgressData = @json($goalsProgressData);
        var goalProgressPerWeek = @json($goalProgressPerWeek);
        var weeklyProgressForAllGoals = @json($weeklyProgressForAllGoals);
        console.log(weeklyProgressForAllGoals);
    </script>

    <script>
        am5.ready(function() {
            function getRandomColor() {
                const letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }

                console.log(color);
                return color;
            }
            function createGauge(containerId, goalData) {
                var root = am5.Root.new(containerId);

                root.setThemes([
                    am5themes_Animated.new(root)
                ]);

                var chart = root.container.children.push(am5radar.RadarChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    innerRadius: am5.percent(70),
                    startAngle: -90,
                    endAngle: 245
                }));

                var cursor = chart.set("cursor", am5radar.RadarCursor.new(root, {
                    behavior: "zoomX"
                }));
                cursor.lineY.set("visible", false);

                var xRenderer = am5radar.AxisRendererCircular.new(root, {
                    //minGridDistance: 50
                });

                xRenderer.labels.template.setAll({
                    radius: 10
                });

                xRenderer.grid.template.setAll({
                    forceHidden: true
                });

                var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                    renderer: xRenderer,
                    min: 0,
                    max: 100,
                    strictMinMax: true,
                    numberFormat: "#'%'",
                    tooltip: am5.Tooltip.new(root, {})
                }));

                var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
                    categoryField: "category",
                    renderer: am5radar.AxisRendererRadial.new(root, {})
                }));
                yAxis.data.setAll([goalData]);

                var series1 = chart.series.push(am5radar.RadarColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    clustered: false,
                    valueXField: "full",
                    categoryYField: "category",
                    fill: root.interfaceColors.get("alternativeBackground")
                }));
                series1.columns.template.setAll({
                    width: am5.p20,
                    fillOpacity: 0.08,
                    strokeOpacity: 0,
                    cornerRadius: 20
                });
                series1.data.setAll([goalData]);

                var series2 = chart.series.push(am5radar.RadarColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    clustered: false,
                    valueXField: "value",
                    categoryYField: "category"
                }));
                series2.columns.template.setAll({
                    width: am5.percent(20),
                    strokeOpacity: 0,
                    tooltipText: "{category}: {valueX}%",
                    cornerRadius: 30,
                    // templateField: "columnSettings"
                    fill: am5.color(getRandomColor()),

                });
                series2.data.setAll([goalData]);

                series1.appear(1000);
                series2.appear(1000);
                chart.appear(1000, 100);
            }
            function createCombinedGauge(containerId, goalData){
                // Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
                var root = am5.Root.new("chartdiv_combined_goals");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
                root.setThemes([
                    am5themes_Animated.new(root)
                ]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/radar-chart/
                var chart = root.container.children.push(am5radar.RadarChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    innerRadius: am5.percent(20),
                    startAngle: -90,
                    endAngle: 180,
                }));

                chart.children.unshift(am5.Label.new(root, {
                    text: "[#ff00ff]Today's Progress",
                    fontSize: 24,
                    fontWeight: "300",
                    textAlign: "center",
                    x: am5.percent(90),
                    y: am5.percent(94),
                    centerX: am5.percent(50),
                    paddingTop: 0,
                    paddingBottom: 0
                }));



                    var data = goalData;

// Add cursor
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Cursor
                var cursor = chart.set("cursor", am5radar.RadarCursor.new(root, {
                    behavior: "zoomX"
                }));

                cursor.lineY.set("visible", false);

// Create axes and their renderers
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_axes
                var xRenderer = am5radar.AxisRendererCircular.new(root, {
                    //minGridDistance: 50
                });

                xRenderer.labels.template.setAll({
                    radius: 10
                });

                xRenderer.grid.template.setAll({
                    forceHidden: true
                });

                var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                    renderer: xRenderer,
                    min: 0,
                    max: 100,
                    strictMinMax: true,
                    numberFormat: "#'%'",
                    tooltip: am5.Tooltip.new(root, {})
                }));


                var yRenderer = am5radar.AxisRendererRadial.new(root, {
                    minGridDistance: 20
                });

                yRenderer.labels.template.setAll({
                    centerX: am5.p100,
                    fontWeight: "500",
                    fontSize: 18,
                    templateField: "columnSettings"
                });

                yRenderer.grid.template.setAll({
                    forceHidden: true
                });

                var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
                    categoryField: "category",
                    renderer: yRenderer
                }));

                yAxis.data.setAll(data);


// Create series
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_series
                var series1 = chart.series.push(am5radar.RadarColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    clustered: false,
                    valueXField: "full",
                    categoryYField: "category",
                    fill: root.interfaceColors.get("alternativeBackground")
                }));

                series1.columns.template.setAll({
                    width: am5.p100,
                    fillOpacity: 0.08,
                    strokeOpacity: 0,
                    cornerRadius: 20
                });

                series1.data.setAll(data);


                var series2 = chart.series.push(am5radar.RadarColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    clustered: false,
                    valueXField: "value",
                    categoryYField: "category"
                }));

                series2.columns.template.setAll({
                    width: am5.p100,
                    strokeOpacity: 0,
                    tooltipText: "{category}: {valueX}%",
                    cornerRadius: 20,
                    templateField: "columnSettings"
                });

                series2.data.setAll(data);

// Animate chart and series in
// https://www.amcharts.com/docs/v5/concepts/animations/#Initial_animation
                series1.appear(1000);
                series2.appear(1000);
                chart.appear(1000, 100);
            }

            function createBarChart(containerId, weeklyGoalProgress, goalTitle="Goal Title"){

                var root = am5.Root.new(containerId);

                const myTheme = am5.Theme.new(root);

                myTheme.rule("AxisLabel", ["minor"]).setAll({
                    dy:1
                });

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
                root.setThemes([
                    am5themes_Animated.new(root),
                    myTheme,
                    am5themes_Responsive.new(root)
                ]);



// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
                var chart = root.container.children.push(am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    paddingTop: 40,
                    paddingLeft:0,
                }));

                chart.children.unshift(am5.Label.new(root, {
                    text: "[#ff00ff]This Week's Progress for " + goalTitle,
                    fontSize: 24,
                    fontWeight: "300",
                    textAlign: "center",
                    x: am5.percent(50),
                    y: am5.percent(-10),
                    // centerX: am5.percent(50),
                    paddingTop: 0,
                    paddingBottom: 0
                }));


// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
                var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
                    behavior: "zoomX"
                }));
                cursor.lineY.set("visible", false);

                var date = new Date();
                date.setHours(0, 0, 0, 0);
                var value = 100;

                function generateData() {
                    value = Math.round((Math.random() * 10 - 5) + value);
                    am5.time.add(date, "day", 1);
                    return {
                        date: date.getTime(),
                        value: value
                    };
                }

                function generateDatas(count) {
                    var data = [];
                    for (var i = 0; i < count; ++i) {
                        data.push(generateData());
                    }
                    return data;
                }


// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
                var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
                    maxDeviation: 0,
                    baseInterval: {
                        timeUnit: "day",
                        count: 1
                    },
                    renderer: am5xy.AxisRendererX.new(root, {
                        minorGridEnabled:true,
                        minorLabelsEnabled:true
                    }),
                    tooltip: am5.Tooltip.new(root, {})
                }));

                // xAxis.set("minorDateFormats", {
                //     "day":"dd",
                //     "month":"MM"
                // });
                xAxis.set("dateFormats", {
                    day: "EEE dd", // Abbreviated weekday (Mon, Tue) and day
                    month: "MM", // Month
                    year: "yyyy" // Year
                });

                // xAxis.get("dateFormats")["day"] = "EEE";


                var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {})
                }));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
                var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                    name: "Series",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "value",
                    valueXField: "date",
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "{valueY}"
                    })
                }));

                series.columns.template.setAll({ strokeOpacity: 0 })


// Add scrollbar
// https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
                chart.set("scrollbarX", am5.Scrollbar.new(root, {
                    orientation: "horizontal"
                }));
                console.log(weeklyGoalProgress);
                var data = weeklyGoalProgress;// generateDatas(7);//

                console.log(data);
                series.data.setAll(data);


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
                series.appear(1000);
                chart.appear(1000, 100);

            }
            // Create a gauge for each goal
            goalsProgressData.forEach(function(goal, index) {
                createGauge("chartdiv" + (index + 1), goal);
            });

            createCombinedGauge("chartdiv_combined_goals", goalsProgressData)

            // createBarChart("chartdiv_bar_chart", goalProgressPerWeek);

            // weeklyProgressForAllGoals.forEach(function(goal, index) {
            //     console.log(goal[0]);
                // createGauge("chartdiv_weekly" + (index + 1), goal);
            // });
            let index = 1;
            for (let goalTitle in weeklyProgressForAllGoals) {
                // console.log(`Progress for ${goalTitle}:` + weeklyProgressForAllGoals[goalTitle]);

                createBarChart("chartdiv_weekly_bar_chart"+index, weeklyProgressForAllGoals[goalTitle], goalTitle);
                ++index;
                // Now loop through each progress entry for this goal
            }

        }); // end am5.ready()
    </script>


@endpush
<x-app-layout :assets="$assets ?? []">
    <h1>User Dashboard</h1>
   	<div class="row">
      	<div class="col-md-12 col-lg-12" style="padding-top: 20px;">


            <div class="row ">

                @foreach($goalsProgressData as $index => $goal)
                    <div class="col-lg-4 col-md-6" >
                        <div class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <div class="card-body">
                                <div id="chartdiv{{ $index + 1 }}" style="width: 100%; height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if(!empty($goalsProgressData))
                <div class="row">
                    <div class="col-lg-12">
                        <div class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <div class="card-body">
                                <div id="chartdiv_combined_goals" class="p-3" style="width: 100%; height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


{{--            <div class="row">--}}
{{--                <div class="col-lg-12">--}}
{{--                    <div class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">--}}
{{--                        <div class="card-body">--}}
{{--                            <div id="chartdiv_bar_chart" class="p-3" style="width: 100%; height: 400px;"></div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}


            @foreach($weeklyProgressForAllGoals as $goalTitle => $progressData)
                <div class="col-lg-12">
                    <div class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">

                        <div class="card-head p-2">
                            {{ $goalTitle }}
                        </div>
                            <div class="card-body">

                                {{-- Use $loop->iteration for 1-indexed or $loop->index for 0-indexed --}}
                                <div id="chartdiv_weekly_bar_chart{{ $loop->iteration }}" style="width: 100%; height: 300px;"></div>
                            </div>


                    </div>
                </div>
            @endforeach
                    <div class="row ">
                <div class="col-lg-3 col-md-6" >
					<div class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
						<div class="card-body">
							<div class="d-flex align-items-center gap-3">
								<div class="rounded p-3" style="background-color: #FFF8ED;">
									<svg width="30" height="32" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M29.0472 20.1036C24.1498 20.6478 22.3763 21.2121 20.5222 22.8849C18.7688 24.477 17.7208 27.0769 17.882 29.4349C17.9828 30.7852 18.668 32.7401 20.1191 35.9244L20.8245 37.4359L19.4137 38.5444C17.3177 40.1768 14.7783 42.8976 13.4079 44.9936C11.3119 48.198 10.1631 50.9793 9.37711 54.8488C8.99418 56.7029 8.95388 57.4083 9.03449 60.4919C9.13526 64.1397 9.39726 65.6109 10.405 68.5735C12.0173 73.2694 15.9674 78.6504 18.8695 80.1015C21.2074 81.2704 20.8648 81.2503 37.27 81.2503C53.6752 81.2503 53.5139 81.2704 55.8115 80.1015C57.1819 79.4163 59.7818 76.7358 61.253 74.5391L62.4219 72.7857H66.7147H71.0074L71.088 75.1437C71.1888 77.804 71.471 78.7109 72.519 79.8194C73.6677 81.0286 74.4134 81.2503 77.5776 81.2503C79.1093 81.2503 80.7216 81.1495 81.1448 81.0286C82.7772 80.5247 84.0469 78.9326 84.3291 77.0381L84.45 76.1111L86.6669 76.0103C89.2063 75.8894 89.9923 75.5669 90.6171 74.398C90.9597 73.7531 91 73.1485 91 66.9814C91 59.5446 90.9597 59.3229 89.7102 58.416C89.1862 58.0331 88.7428 57.9525 86.7677 57.8719L84.45 57.7711L84.3492 56.8642C84.1074 55.01 82.7974 53.3574 81.1448 52.8737C80.7216 52.7327 79.1294 52.6319 77.5776 52.6319C74.5142 52.6319 73.7483 52.8536 72.5794 53.962C71.5314 54.9697 71.1888 56.0983 71.088 58.8191L71.0074 61.298H68.4277H65.8682L65.7271 58.7183C65.3241 50.7576 61.6561 43.6433 55.3882 38.6451C54.5821 38.0002 53.917 37.4561 53.917 37.4158C53.917 37.3956 54.5015 36.005 55.227 34.3322C55.9324 32.6595 56.5975 30.7852 56.6982 30.1805C57.4238 25.6258 54.0782 21.3532 49.201 20.5873C43.8804 19.7409 34.4081 19.5192 29.0472 20.1036ZM42.2278 22.8042C49.1607 23.3282 51.4179 23.9329 52.7078 25.6862C53.5139 26.7745 53.9976 28.4473 53.8364 29.5155C53.7155 30.2813 51.6396 35.4205 51.3172 35.7631C51.2164 35.8639 49.2212 34.9771 48.7173 34.5741C48.677 34.5539 49.1406 33.3648 49.7452 31.9339L50.8536 29.3542L50.5916 28.2458C50.1079 26.1699 50.0878 26.1699 47.1453 25.7467C40.555 24.7995 34.267 24.8196 27.6163 25.7467C25.0568 26.1095 24.7545 26.2505 24.3917 27.097C23.7266 28.7093 23.7871 29.3945 24.8754 31.9138C25.4397 33.2238 25.9032 34.3725 25.9032 34.4733C25.9032 34.6748 23.4848 35.9848 23.3638 35.8438C23.3235 35.8035 22.7391 34.5136 22.074 32.9618C21.0462 30.5635 20.8648 29.979 20.8648 28.9512C20.8648 26.8149 22.1143 24.9002 24.0894 24.0336C26.4877 22.9856 35.9398 22.3407 42.2278 22.8042ZM45.6136 28.5078C46.6415 28.669 47.5283 28.8504 47.6089 28.931C47.7298 29.0519 47.2864 30.3015 46.2989 32.4579C45.7346 33.6671 45.9764 33.647 43.034 32.9416C41.8449 32.6595 40.6357 32.599 37.2901 32.599C33.6221 32.599 32.7958 32.6595 31.1432 33.0424C30.0952 33.2842 29.148 33.4858 29.0271 33.4858C28.9263 33.4858 28.3821 32.4781 27.838 31.2487C27.0721 29.5759 26.8908 28.9915 27.0923 28.8705C27.2334 28.7698 28.4426 28.5884 29.7929 28.4675C31.1432 28.3264 32.4331 28.1853 32.6547 28.145C33.6423 27.9636 43.9006 28.2458 45.6136 28.5078ZM42.2681 35.8236C49.0801 37.1134 55.0658 41.0636 58.8345 46.7268C61.3134 50.4754 62.6033 54.486 62.6436 58.678L62.6839 61.1972H57.4439H52.2039L52.083 58.678C52.0225 57.2874 51.9016 55.9774 51.8009 55.7557C51.297 54.5868 50.4304 53.6396 49.4026 53.1357C48.3344 52.6117 48.2135 52.6117 45.2106 52.6722C42.1472 52.7327 42.0867 52.7327 41.0992 53.3373C39.9101 54.083 39.0233 55.4333 38.842 56.7836L38.7009 57.7711L36.484 57.8719C33.8841 57.9928 33.1787 58.2951 32.554 59.5849C32.1711 60.3709 32.1509 60.8546 32.1509 66.9411C32.1509 73.0477 32.1711 73.5112 32.554 74.2972C33.1989 75.6274 34.0857 76.0103 36.5646 76.0103C38.8218 76.0103 38.7009 75.9297 38.9024 77.4614L39.0233 78.4288H31.1634C23.0414 78.4288 21.8725 78.328 20.4012 77.5823C18.5874 76.6754 16.2092 73.8337 14.5365 70.5889C11.8157 65.3086 11.1708 59.7663 12.6219 53.8814C15 44.2479 23.8878 36.6902 34.267 35.5011C36.3227 35.2795 40.1721 35.4205 42.2681 35.8236ZM48.0724 55.7557C48.3949 55.917 48.7576 56.2394 48.8786 56.441C49.1607 56.9851 49.1607 76.8971 48.8786 77.4412C48.4755 78.1668 47.6089 78.4288 45.4524 78.4288C44.0013 78.4288 43.2355 78.3481 42.8123 78.1063C41.6433 77.5017 41.6232 77.3606 41.6232 66.9814C41.6232 60.4717 41.7038 57.3277 41.8449 56.8843C42.2278 55.7759 43.0138 55.4534 45.4121 55.4534C46.8632 55.4534 47.6693 55.534 48.0724 55.7557ZM80.8626 56.1185L81.4269 56.6626V66.9411V77.2195L80.8626 77.7637C80.3386 78.2877 80.1774 78.328 78.2628 78.3884C75.804 78.4892 74.8971 78.2474 74.4134 77.4412C74.1111 76.8971 74.0708 75.9901 74.0708 66.9612C74.0708 59.6051 74.1313 56.9448 74.3127 56.5417C74.7157 55.655 75.5017 55.4534 78.0411 55.4937C80.2177 55.5542 80.3386 55.5743 80.8626 56.1185ZM38.7009 66.9411V73.088H36.9878H35.2747L35.2143 67.1426C35.1941 63.8777 35.2143 61.0763 35.2747 60.9352C35.3352 60.7539 35.8189 60.6934 37.0281 60.7337L38.7009 60.7942V66.9411ZM87.9769 66.9411V73.2089L86.2235 73.1485L84.45 73.088L84.3895 67.1426C84.3694 63.8777 84.3895 61.0965 84.45 60.9554C84.5105 60.774 85.0546 60.6934 86.2639 60.6934H87.9769V66.9411ZM71.0477 66.9411V69.7626H61.5754H52.1032V66.9411V64.1196H61.5754H71.0477V66.9411ZM58.7539 72.8865C58.7539 73.1283 57.1215 75.184 56.2145 76.1111C55.227 77.0986 53.7558 77.9048 52.5062 78.1466C51.8412 78.2877 51.8412 78.2877 51.9621 77.6226C52.0427 77.2598 52.1032 76.0304 52.1032 74.8817V72.7857H55.4285C57.2625 72.7857 58.7539 72.826 58.7539 72.8865Z" fill="#EC7E4A"/>
									</svg>
								</div>
								<div class="text-right dashboard-show-data">
									<h5 class="counter">{{ __('message.equipment') }}</h5>
									<h4 class="counter" >{{ $data['dashboard']['total_equipment'] }}</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="800">
						<div class="card-body">
							<div class="d-flex align-items-center gap-3">
								<div class="rounded p-3" style="background-color: #FFF8ED;">
									<svg width="30" height="32" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M14.9961 87.8677V66.1677C14.9961 64.6213 16.2497 63.3677 17.7961 63.3677H33.1961C34.7425 63.3677 35.9961 64.6213 35.9961 66.1677V87.8677" stroke="#EC7E4A" stroke-width="4"/>
										<path d="M35.9961 87.8677V45.1677C35.9961 43.6213 37.2497 42.3677 38.7961 42.3677H57.6961C59.2425 42.3677 60.4961 43.6213 60.4961 45.1677V87.8677" stroke="#EC7E4A" stroke-width="4"/>
										<path d="M60.4947 87.8677V24.1677C60.4947 22.6213 61.7483 21.3677 63.2947 21.3677H82.1947C83.7411 21.3677 84.9947 22.6213 84.9947 24.1677V87.8677" stroke="#EC7E4A" stroke-width="4"/>
									</svg>
								</div>
								<div class="text-right dashboard-show-data">
									<h5 class="counter">{{ __('message.level') }}</h5>
									<h4 class="counter" >{{ $data['dashboard']['total_level'] }}</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="900">
						<div class="card-body">
							<div class="d-flex align-items-center gap-3">
								<div class="rounded p-3" style="background-color: #FCF4FF;">
									<svg width="30" height="32" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M15.0785 47.8842C25.9561 46.6495 30.4218 48.2748 38.6509 53.7951M62.2232 59.0334L56.5919 34.2801C56.345 33.1949 55.1595 32.6145 54.1509 33.0852L44.3453 37.6608C43.4951 38.0575 42.5155 38.1069 41.6541 37.7353C39.0814 36.6254 37.3694 35.3588 35.5635 33.0628C34.7011 31.9663 34.4293 30.5239 34.7302 29.1617C35.5838 25.2972 36.4758 22.695 38.2045 19.1111C38.4767 18.5468 39.039 18.1778 39.6647 18.1456C46.6661 17.7855 51.1304 18.0359 57.8948 19.5493C59.0049 19.7977 60.0029 20.4287 60.7221 21.3101C77.4516 41.8106 82.7966 52.9052 84.749 69.0535C84.8668 70.0272 84.5497 71.0096 83.8894 71.7349C69.8489 87.1572 59.6876 89.1263 38.6509 81.0605C31.3365 86.2921 25.8127 87.3527 15.9515 85.4258" stroke="#EC7E4A" stroke-width="3" stroke-linecap="round"/>
											<path d="M30.4114 56.9722C36.8138 53.1889 53.4949 49.7364 66.2065 62.7274" stroke="#EC7E4A" stroke-width="3" stroke-linecap="round"/>
										<path d="M41.5993 26.9047C41.1022 26.242 40.162 26.1077 39.4993 26.6047C38.8365 27.1018 38.7022 28.042 39.1993 28.7047L41.5993 26.9047ZM38.6122 36.0372L42.3147 33.5689L40.6506 31.0727L36.9481 33.5411L38.6122 36.0372ZM43.111 28.9203L41.5993 26.9047L39.1993 28.7047L40.711 30.7203L43.111 28.9203ZM42.3147 33.5689C43.8652 32.5352 44.2291 30.4111 43.111 28.9203L40.711 30.7203C40.7957 30.8333 40.7681 30.9944 40.6506 31.0727L42.3147 33.5689Z" fill="#EC7E4A"/>
									</svg>
								</div>
								<div class="text-right dashboard-show-data">
									<h5 class="counter">{{ __('message.bodypart') }}</h5>
									<h4 class="counter" >{{ $data['dashboard']['total_bodypart'] }}</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1000">
						<div class="card-body">
							<div class="d-flex align-items-center gap-3">
								<div class="rounded p-3" style="background-color: #FFF5F5;">
									<svg width="30" height="32" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M27.3593 24.5637C26.2131 24.9563 25.271 25.7884 24.7215 26.8718C24.2662 27.7667 24.2505 27.8766 24.172 30.6243L24.0935 33.4505L21.1888 33.529C18.551 33.6075 18.2213 33.6389 17.609 33.9686C16.5727 34.5181 15.772 35.3346 15.3637 36.2452C15.0183 36.9989 14.9869 37.2972 14.9398 40.2333L14.8927 43.4206H11.831C8.78505 43.4206 8.76935 43.4206 8.39252 43.8131L8 44.1899V50.2348V56.2796L8.40822 56.5936C8.80075 56.9077 9.03626 56.9234 11.8624 56.9234H14.8927L14.9398 60.1735C14.9869 63.3607 15.0026 63.455 15.4108 64.2243C15.6307 64.6639 16.1488 65.3234 16.5256 65.6688C17.7189 66.7679 18.0643 66.8621 21.3144 66.9406L24.172 67.0034V69.7039C24.172 72.4202 24.172 72.4202 24.643 73.3779C25.1611 74.3985 25.8363 75.0893 26.8882 75.6389C27.5006 75.9686 27.7518 76 30.2168 76C32.8075 76 32.9017 75.9843 33.6867 75.5761C34.6445 75.0736 35.3824 74.2729 35.8849 73.1895L36.2617 72.3888V64.6482V56.9234H49.9215H63.5813V63.9417C63.5813 67.8198 63.6441 71.3839 63.7383 71.8707C64.0052 73.535 64.8374 74.7439 66.2662 75.529C67.114 76 67.114 76 69.7832 76C72.2482 76 72.4994 75.9686 73.1118 75.6389C74.1637 75.0893 74.8389 74.3985 75.357 73.3779C75.828 72.4202 75.828 72.4202 75.828 69.7039V67.0034L78.7013 66.9406C81.4333 66.8935 81.5903 66.8621 82.3439 66.4695C83.3488 65.9357 84.071 65.2292 84.5892 64.2243C84.9974 63.455 85.0131 63.3607 85.0602 60.1735L85.1073 56.9234H88.1376C90.9637 56.9234 91.1992 56.9077 91.5918 56.5936L92 56.2796V50.2505C92 44.5667 91.9843 44.2213 91.7017 43.8759C91.4034 43.4991 91.3406 43.4991 88.2632 43.452L85.1073 43.4049L85.0602 40.2333C85.0131 37.4071 84.9817 36.9832 84.6834 36.3394C84.2123 35.3503 83.443 34.5181 82.5166 34.0314C81.7473 33.6232 81.5903 33.6075 78.8112 33.529L75.9065 33.4505L75.828 30.6243C75.7495 28.0336 75.7181 27.7353 75.3727 27.0288C74.886 26.0396 73.9596 25.1133 73.0176 24.6893C72.0284 24.234 68.4486 24.1084 67.1454 24.4852C65.8579 24.8621 64.8217 25.757 64.1779 27.0445L63.6598 28.1121L63.6127 35.7585L63.5656 43.4206H49.9215H36.2774L36.2303 35.523L36.1832 27.6411L35.7121 26.7933C35.1312 25.757 34.0164 24.8307 32.9331 24.5009C31.7555 24.1555 28.4426 24.1869 27.3593 24.5637ZM32.8075 26.8875C33.1215 27.0759 33.5297 27.5155 33.7181 27.8609L34.0636 28.4733L34.0322 50.3761C33.985 72.2318 33.985 72.2946 33.6553 72.7185C32.9017 73.7234 32.6819 73.8019 30.2168 73.8019C27.6733 73.8019 27.3907 73.7077 26.7312 72.5301L26.3701 71.9178V50.2662C26.3701 28.7559 26.3701 28.5989 26.6841 27.9394C27.2807 26.7305 27.9873 26.4636 30.4523 26.5107C32.0067 26.5421 32.3364 26.5892 32.8075 26.8875ZM72.4052 26.9032C72.735 27.1073 73.1118 27.5312 73.3002 27.9394C73.6299 28.5989 73.6299 28.6774 73.6299 50.1877V71.7764L73.2845 72.4673C72.6407 73.692 72.3738 73.8019 69.7832 73.8019C67.3181 73.8019 67.0983 73.7234 66.3447 72.7185C66.015 72.2946 66.015 72.2318 65.9678 50.3761L65.9364 28.4733L66.2819 27.8609C66.9256 26.6991 67.585 26.4636 70.0187 26.5107C71.5888 26.5421 71.9028 26.5892 72.4052 26.9032ZM24.1406 50.2034L24.0935 64.6953L21.9267 64.7424C19.5402 64.7895 18.7708 64.6482 18.0486 64.0516C17.0594 63.2037 17.1065 63.9574 17.1065 50.2505C17.1065 36.2295 17.0437 37.0617 18.2527 36.1824L18.8807 35.7271H21.5342H24.172L24.1406 50.2034ZM81.4804 35.9626C81.7316 36.0882 82.1398 36.465 82.3753 36.7791L82.8149 37.3443L82.8621 49.8422C82.8935 58.3207 82.8621 62.5286 82.7365 62.9211C82.5323 63.6277 81.6845 64.397 80.868 64.6168C80.5069 64.711 79.2665 64.7581 78.0733 64.7424L75.9065 64.6953L75.8594 50.2034L75.828 35.7271H78.4187C80.4284 35.7271 81.135 35.7742 81.4804 35.9626ZM14.877 50.2034L14.8299 54.6467H12.5533H10.2766L10.2295 50.2034L10.1981 45.7757H12.5533H14.9084L14.877 50.2034ZM63.5813 50.2505V54.7252L49.9686 54.6938L36.3402 54.6467L36.2931 50.2034L36.2617 45.7757H49.9215H63.5813V50.2505ZM89.7705 50.2034L89.7234 54.6467H87.4467H85.1701L85.123 50.2034L85.0916 45.7757H87.4467H89.8019L89.7705 50.2034Z" fill="#EC7E4A"/>
									</svg>
								</div>
								<div class="text-right dashboard-show-data">
									<h5 class="counter">{{ __('message.workouttype') }}</h5>
									<h4 class="counter" >{{ $data['dashboard']['total_workouttype'] }}</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1100">
						<div class="card-body">
							<div class="d-flex align-items-center gap-3">
								<div class="rounded p-3" style="background-color: #EEF4FF;">
									<svg width="30" height="32" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M24.407 17.4434C21.6954 18.3138 19.4357 20.54 18.4983 23.2182C18.1803 24.0886 18.1133 24.6577 18.1133 26.1474C18.1133 28.3736 18.4649 29.5788 19.6365 31.3028L20.4065 32.441L19.2683 35.9896C18.649 37.948 17.4271 41.7141 16.5734 44.3923C15.703 47.0537 15 49.4473 15 49.6983C15 50.3679 17.0086 55.674 17.3266 55.8413C17.5442 55.9753 19.7035 56.6615 29.8972 59.8753L30.8178 60.1599L31.2865 61.7333C31.5376 62.6037 32.4582 65.784 33.3286 68.8136L34.9187 74.3206V79.6601C34.9187 82.8237 34.9857 85.1336 35.0861 85.3177C35.1698 85.4851 35.4878 85.7027 35.7724 85.8031C36.4921 86.0542 62.6543 86.0709 63.3071 85.8199C64.1942 85.4683 64.1942 85.4851 64.2109 79.5764V74.0695L65.1148 70.9561C67.5084 62.7543 68.2449 60.2938 68.3621 60.1933C68.429 60.1264 71.4252 59.1556 75.0072 58.0341C78.606 56.9126 81.6691 55.8581 81.8365 55.7074C82.188 55.3559 84.1297 50.3009 84.1297 49.6983C84.1297 49.4807 83.0751 46.0661 81.803 42.1493C78.8905 33.278 78.7232 32.7089 78.7232 32.508C78.7232 32.4243 79.0579 31.8552 79.4764 31.2526C80.5309 29.6959 81.0163 28.0388 81.0163 26.1474C80.9996 23.6534 80.2463 21.7787 78.5223 19.904C74.5553 15.6189 67.5921 16.1546 64.3281 20.992C61.4826 25.21 62.5371 30.9178 66.6882 33.7466L67.6256 34.3827L68.4123 37.2115C70.2368 43.639 70.7724 45.6644 70.7055 45.7313C70.6385 45.7983 64.4285 46.6854 59.6414 47.3215C58.2019 47.5056 56.8293 47.6228 56.595 47.5558C56.143 47.4387 55.3396 46.6017 55.3396 46.2335C55.3396 46.0996 55.9254 45.4133 56.6452 44.6936C57.5323 43.8064 58.1516 43.003 58.5701 42.1995C59.5911 40.2077 59.725 39.4879 59.8087 35.1861C59.9259 29.5955 59.6246 27.771 58.2521 25.6955C56.846 23.5864 54.3855 22.0297 51.9082 21.695C49.8996 21.4104 46.9202 21.5945 45.4974 22.0967C42.451 23.1512 40.1746 25.7457 39.505 28.8925C39.1703 30.5161 39.187 37.6969 39.5385 39.421C39.957 41.53 40.7772 43.003 42.3841 44.6266C43.539 45.7648 43.7733 46.0828 43.6896 46.4009C43.5725 46.8528 43.0201 47.4052 42.518 47.5558C42.3171 47.6228 40.4759 47.4387 38.417 47.1541C36.3582 46.8696 33.2784 46.4511 31.5878 46.2335C29.8972 45.9992 28.4577 45.7816 28.4075 45.7313C28.307 45.6309 28.3238 45.6142 30.165 39.036L31.4873 34.3325L32.3912 33.7634C34.9187 32.123 36.5758 28.7418 36.375 25.6452C36.1574 22.6323 34.45 19.8203 31.9225 18.2971C29.7465 16.9747 26.8341 16.6567 24.407 17.4434ZM29.5624 20.0211C32.3577 21.0924 33.8642 23.4692 33.7136 26.5826C33.5964 29.0599 32.3745 30.9178 30.1315 32.0226C25.1937 34.4664 19.6533 30.2483 20.7748 24.8753C21.1765 22.9169 22.2143 21.4439 23.8379 20.5065C25.6289 19.4855 27.6877 19.3181 29.5624 20.0211ZM73.099 19.7031C75.1579 20.0881 76.9321 21.4774 77.836 23.3856C78.3214 24.3899 78.3549 24.6075 78.3549 26.2311C78.3549 27.838 78.3047 28.0723 77.8695 28.9929C75.1077 34.5836 66.8724 33.6797 65.4496 27.6204C65.1818 26.5491 65.1818 26.2143 65.3492 25.1933C65.7341 23.0006 66.9895 21.2598 68.8977 20.2889C70.287 19.5859 71.5424 19.4185 73.099 19.7031ZM53.331 24.7581C54.5864 25.3774 55.5907 26.2646 56.1765 27.3023C56.8628 28.4908 56.8293 28.6582 56.0091 28.2397C54.2348 27.3358 52.1593 27.4697 50.2176 28.6079C48.7781 29.4449 47.0373 30.1981 45.6313 30.6166C44.3927 30.9681 42.0493 31.2024 41.9154 30.9848C41.8652 30.9011 41.8819 30.3655 41.9656 29.7796C42.3004 27.3693 44.1416 25.2268 46.5184 24.4568C47.0206 24.2894 47.9914 24.2392 49.8159 24.2727C52.2597 24.3062 52.4606 24.3396 53.331 24.7581ZM55.2224 30.65C55.4233 30.8007 55.9589 31.3196 56.3941 31.8217L57.1808 32.7423V35.5544C57.1808 38.651 56.9967 39.7557 56.2267 41.1618C55.6074 42.3167 54.0507 43.7227 52.7117 44.3588C51.6237 44.8609 51.473 44.8944 49.5648 44.8944C47.6064 44.8944 47.5227 44.8777 46.3008 44.2918C44.945 43.6558 43.7733 42.618 43.0201 41.4463C42.2167 40.1574 41.9321 38.9355 41.8317 36.2574L41.7313 33.8136L43.3884 33.6295C46.1669 33.3114 48.4936 32.5415 50.9039 31.1689C51.6404 30.7337 52.5108 30.332 52.8288 30.265C53.5318 30.1144 54.6868 30.3153 55.2224 30.65ZM24.2898 34.8514C25.1937 35.2029 25.7795 35.3033 26.9345 35.32C28.0225 35.3368 28.3907 35.387 28.3907 35.5544C28.3907 35.6883 27.9555 37.3454 27.4032 39.2536C25.11 47.3717 25.11 47.3717 25.9302 47.8906C26.1478 48.0412 28.374 48.4262 31.32 48.8279C34.0818 49.2129 36.4084 49.5979 36.4754 49.6649C36.7265 49.8992 36.8102 52.41 36.5926 53.6319C36.3247 55.3057 35.4543 57.013 34.3329 58.1345L33.4123 59.0551L32.8599 58.5028C32.3745 57.9839 31.7217 57.7495 25.7963 55.8748C22.2143 54.7366 19.2013 53.7323 19.1177 53.6654C19.0172 53.5817 18.6322 52.6443 18.2473 51.573L17.5442 49.6481L19.7202 43.003C20.9087 39.354 22.0301 35.8724 22.231 35.3033C22.4988 34.4664 22.6327 34.2488 22.8503 34.3157C23.001 34.366 23.6538 34.617 24.2898 34.8514ZM78.991 41.8648C80.3468 46.0326 81.4515 49.5477 81.4515 49.6816C81.4348 49.8155 81.1167 50.7696 80.7318 51.8241L80.0287 53.7156L73.3669 55.8413C69.7011 57.013 66.5711 58.0843 66.4037 58.2349C66.2196 58.3856 66.002 58.6367 65.9183 58.804C65.7509 59.0886 65.6672 59.0551 64.8805 58.2852C63.8929 57.3143 63.3573 56.4941 62.8384 55.0881C62.5371 54.2679 62.4869 53.7156 62.4701 51.8911C62.4534 50.6859 62.4869 49.6481 62.5371 49.5979C62.5873 49.5477 64.5959 49.2464 66.9728 48.9284C69.3664 48.5936 71.693 48.2756 72.1617 48.2254C73.0488 48.0915 73.7518 47.5726 73.7518 47.0369C73.7518 46.8528 73.484 45.7816 73.1493 44.6433C72.8312 43.4884 72.145 41.0446 71.6428 39.2034C71.1406 37.3621 70.6887 35.705 70.6385 35.5376C70.5381 35.2531 70.605 35.2364 71.2076 35.3368C72.1617 35.4874 74.1033 35.1694 75.2248 34.7007C75.7605 34.4831 76.2626 34.299 76.363 34.2823C76.4635 34.2823 77.6352 37.6802 78.991 41.8648ZM47.5562 47.4052C48.8283 47.7232 50.1005 47.7232 51.5734 47.4052C53.0464 47.0872 52.946 47.0872 53.0632 47.5391C53.2138 48.1752 54.4357 49.4305 55.2726 49.8155C55.9924 50.167 56.0259 50.2005 55.9254 50.7194C55.5739 52.3932 53.9168 54.3516 52.1928 55.1551C51.3558 55.5568 51.0378 55.607 49.5648 55.607C48.0082 55.607 47.8073 55.5735 46.7695 55.0546C45.3468 54.3684 43.9575 52.8954 43.472 51.5898C42.9866 50.3009 43.0201 50.1001 43.7231 49.849C44.5433 49.5477 45.5141 48.6438 45.8824 47.8236C46.2171 47.0704 46.1837 47.0704 47.5562 47.4052ZM59.792 51.8074C59.8255 54.7868 60.6122 57.1972 62.2358 59.256C62.7045 59.8586 64.2612 61.1976 64.847 61.5324C65.1483 61.6998 65.2487 61.2981 63.1062 68.663L61.7002 73.5339V78.4215V83.3091H49.5648H37.4295V78.4549V73.5841L35.7556 67.8595C34.8183 64.7127 34.0316 62.0513 33.9981 61.9341C33.9646 61.8337 34.4166 61.3985 34.9857 60.9633C37.781 58.9212 39.2707 55.7242 39.2707 51.8074V49.9829L39.8063 50.0331C40.3252 50.0833 40.3587 50.1335 40.66 51.255C42.7523 59.256 52.9293 60.8629 57.3482 53.8997C57.8001 53.1799 58.3692 51.64 58.6036 50.4683C58.6705 50.1335 58.8044 49.9996 59.1559 49.9494C59.407 49.9159 59.6414 49.8657 59.6916 49.8657C59.7418 49.849 59.792 50.7194 59.792 51.8074Z" fill="#EC7E4A"/>
										<path d="M25.4105 22.2306C23.8036 23.0341 22.9667 24.3731 22.9667 26.1474C22.95 27.5032 23.2847 28.3067 24.1719 29.2105C25.0255 30.0475 25.9294 30.4157 27.2182 30.4157C31.8548 30.4157 33.2106 24.2225 28.9925 22.2306C27.9547 21.7452 26.3981 21.7452 25.4105 22.2306ZM27.9715 24.641C29.5616 25.4109 29.0093 27.9049 27.235 27.9049C25.5277 27.9049 24.8247 25.595 26.2642 24.7246C26.917 24.3229 27.2517 24.3062 27.9715 24.641Z" fill="#EC7E4A"/>
										<path d="M69.9344 22.3143C66.7541 23.8877 66.7708 28.4071 69.9344 29.9805C71.0224 30.5162 72.6293 30.5664 73.667 30.0977C75.6087 29.2273 76.613 27.0346 76.0272 24.9925C75.2739 22.4315 72.3112 21.1259 69.9344 22.3143ZM73.1314 25.0594C74.1525 26.1976 73.3323 27.905 71.7756 27.905C70.7211 27.905 69.9511 26.7667 70.2524 25.662C70.6039 24.3564 72.1941 24.0216 73.1314 25.0594Z" fill="#EC7E4A"/>
									</svg>
								</div>
								<div class="text-right dashboard-show-data">
									<h5 class="counter">{{ __('message.exercise') }}</h5>
									<h4 class="counter" >{{ $data['dashboard']['total_exercise'] }}</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-3 col-md-6">
					<div class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1200">
						<div class="card-body">
							<div class="d-flex align-items-center gap-3">
								<div class="rounded p-3" style="background-color: #FFF5F5;">
									<svg width="30" height="32" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M36.7977 15.1493C35.8574 15.4675 35.3656 15.7713 34.787 16.4222C34.0203 17.2612 33.6731 18.1002 33.6587 19.1852C33.6442 20.9789 33.919 21.4563 36.7688 24.6387L38.3166 26.3601L37.3474 26.4614C35.9008 26.606 34.7436 26.9243 33.6876 27.4884C31.4454 28.6601 29.9555 30.4105 29.0875 32.8841C28.7693 33.8388 28.7114 34.2149 28.7114 35.7194C28.7114 37.7012 28.9139 38.5836 29.7529 40.2037C30.6064 41.8094 32.6027 43.7044 34.0348 44.2396C34.2807 44.3264 33.8467 44.8182 31.1995 47.4799C29.4781 49.2013 27.9592 50.8504 27.8146 51.1253C27.4529 51.863 27.3661 53.6567 27.6699 54.5536C28.0749 55.7832 28.6825 56.304 32.2121 58.5172C34.0203 59.6455 35.7417 60.716 36.031 60.904L36.5662 61.2367L36.3637 61.7864C36.248 62.0902 35.5536 65.6198 34.8304 69.6413C33.4561 77.2791 33.4272 77.5539 33.9046 78.8993L34.1071 79.4779H31.3297C28.0171 79.4779 27.6844 79.5213 26.7152 80.1144C25.6158 80.7653 25.095 81.6622 25.0372 83.0364C24.9938 83.6295 25.0516 84.3094 25.1529 84.6276C25.3843 85.3365 26.368 86.3924 27.1057 86.7252C27.6554 86.9855 29.0731 87 62.0112 87C76.3357 87 81.6012 86.9711 82.0062 86.7975C82.7151 86.5082 83.6842 85.5679 83.988 84.9025C84.3207 84.1937 84.3497 82.5735 84.0604 81.8647C83.7132 81.0402 83.149 80.4181 82.3534 79.9697L81.5867 79.5502L80.6357 79.5068L71.4573 79.4634L71.6742 78.8559C71.9925 77.9445 72.0648 76.7583 71.8478 75.9338C71.7321 75.5288 67.2767 66.7337 61.9244 56.3763L52.2036 37.5276V33.5495C52.2036 29.9476 52.1746 29.5136 51.9142 28.8193C51.6828 28.1828 50.6847 26.9966 46.2003 21.9481C41.3978 16.538 40.6889 15.8147 39.9801 15.4675C39.0399 15.0046 37.6656 14.8599 36.7977 15.1493ZM38.6782 17.7386C39.0254 17.8977 48.7463 28.6746 49.3394 29.557C49.5708 29.8897 49.5997 30.3816 49.5997 34.0992V38.2653L54.5397 47.8271L59.4797 57.3889C68.4484 74.791 69.3453 76.5703 69.3308 77.1778C69.3019 79.3043 66.5824 80.288 65.2226 78.6533C65.0201 78.4219 62.6911 74.0099 60.0294 68.8456C57.3822 63.667 55.0533 59.2983 54.8508 59.1103C54.1853 58.4883 53.9394 58.5895 48.5148 61.8443C44.5657 64.2022 43.3651 64.9833 43.2493 65.2726C43.1626 65.4896 42.5984 68.3972 41.9908 71.7388C41.3833 75.0803 40.8336 77.9445 40.7468 78.1036C39.6908 80.1722 36.7688 79.8106 36.2335 77.5684C36.1612 77.2357 38.6493 62.9003 38.8084 62.7412C38.8373 62.7122 39.17 62.8713 39.5606 63.0883C40.1971 63.45 40.4141 63.4934 41.6437 63.4934C42.7864 63.4934 43.1192 63.4355 43.611 63.1751C44.5802 62.6544 45.072 62.1625 45.5494 61.2078C45.9544 60.3977 45.9978 60.1952 45.9689 59.0814L45.9399 57.8518L46.9236 57.2587C47.9217 56.6511 48.2834 56.2316 48.2978 55.6385C48.2978 55.1612 43.5821 45.8164 43.2493 45.6283C43.0902 45.556 42.7141 45.4837 42.4103 45.4837C41.8896 45.4837 41.716 45.6428 38.6493 48.7095C35.6115 51.7328 35.4235 51.9643 35.4235 52.4561C35.4235 52.8033 35.5392 53.107 35.7562 53.3385C35.9298 53.541 37.6222 54.6549 39.5172 55.8266C41.933 57.331 43.0179 58.0832 43.177 58.3725C43.611 59.226 43.3072 60.3399 42.5116 60.7449C41.5858 61.2223 41.4411 61.1644 35.9298 57.7216C30.3605 54.2498 30.0712 54.0184 30.0712 52.9479C30.0712 52.3838 30.158 52.2825 33.7165 48.7095L37.3618 45.0497H38.331C39.5462 45.0497 41.2676 44.6157 42.4103 44.0082C42.9745 43.7189 43.7701 43.0679 44.5368 42.3012C45.9399 40.8981 46.533 39.871 47.0249 37.9326C47.7915 34.9093 46.7934 31.3363 44.6236 29.2822C44.2475 28.9205 42.2512 26.7507 40.1826 24.4507C36.0744 19.8651 35.9153 19.6191 36.3782 18.6499C36.8122 17.7241 37.7958 17.3336 38.6782 17.7386ZM39.6908 29.3111C40.66 29.5426 41.9474 30.3237 42.7575 31.1338C45.2022 33.6219 45.2745 37.6144 42.9166 40.2037C39.0977 44.3987 32.3712 42.4025 31.3586 36.7898C30.8668 34.0703 32.458 31.0614 35.0474 29.774C36.5373 29.0507 38.0273 28.9061 39.6908 29.3111ZM43.7267 52.0945L45.2456 55.1467L44.8116 55.436L44.3632 55.7109L42.1644 54.3511C40.9638 53.5844 39.7342 52.8177 39.416 52.6152L38.8518 52.2536L40.5009 50.6045C41.3978 49.7076 42.15 48.9843 42.1644 48.9988C42.1789 49.0133 42.8732 50.402 43.7267 52.0945ZM58.0476 70.784C60.4779 75.4998 62.4741 79.3911 62.4741 79.42C62.4741 79.4489 58.12 79.4779 52.7822 79.4779C47.4444 79.4779 43.0902 79.4634 43.0902 79.4345C43.0902 79.4055 43.1915 79.1018 43.3072 78.7401C43.4229 78.393 43.9726 75.5288 44.5368 72.3897C45.1154 69.2507 45.5783 66.6758 45.5928 66.6613C45.6072 66.6613 47.372 65.5909 49.5274 64.3179C51.6683 63.0305 53.4621 62.0323 53.5199 62.0902C53.5778 62.1481 55.6174 66.0538 58.0476 70.784ZM81.066 82.2987C81.7459 82.6458 81.876 83.4993 81.3408 84.0635L81.037 84.3962H62.0546H28.2775L27.9447 84.0779C27.3806 83.5427 27.5252 82.6458 28.263 82.2553C28.4655 82.1396 38.0707 82.0962 62.0112 82.0817C76.2923 82.0817 80.7188 82.1106 81.066 82.2987Z" fill="#EC7E4A"/>
									</svg>
								</div>
								<div class="text-right dashboard-show-data">
									<h5 class="counter">{{ __('message.workout') }}</h5>
									<h4 class="counter" >{{ $data['dashboard']['total_workout'] }}</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
      	<div class="col-md-12 col-lg-12">
         	<div class="row">

         	</div>
      	</div>
   	</div>
</x-app-layout>
