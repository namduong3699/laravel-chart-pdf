<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Report</title>

        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/common.css">

        <style>
        body {
                font-family: 'Nunito';
            }
            html {
                font-size: 14px;
                -webkit-print-color-adjust: exact;
            }
            .axis-grid line {
                stroke: #747d8c;
            }
        </style>
    </head>
    <body class="d-flex justify-content-center" style="background-color: #eee;">
        <div style="width: 1202px; height: 933px; background-color: white;">
            <main class="page position-relative">
                <div class="container-fluid">
                    <h2 class="page__title">
                        <span class="bg-white pr-5">
                            <span class="font-weight-bold">Health: </span> Carbon Monoxide
                        </span>
                    </h2>
                    <section class="row">
                        <aside class="aside col-3">
                            <h4 class="aside__title">Test results</h4>
                            <p>
                                <strong>What We Found:</strong> Carbon Monoxide levels were below 5 ppm.
                            </p>
                            <p class="text-box text-2xl bg-green text-center text-white">
                                No Action Necessary
                            </p>
                            <p class="link text-lg text-center">Why is no action necessary?</p>
                            <p>Carbon monoxide levels are a cause for concern when average levels are above 5 ppm (8-hour average). When levels (8-hour average) are above 20 ppm, immediate action should be considered.</p>
                            <p class="font-italic">Carbon monoxide is a colorless, odorless, poisonous gas produced by combustion. When people are exposed to relatively low levels (for an 8 hour period or more), it can cause headaches and nausea. At relatively high levels it can cause memory problems and ultimately death.</p> 
                            <div class="text-sm">
                                <p class="m-0">Source: US Environmental Protection Agency; World Health Organization (WHO); Indoor Air Quality Association (IAQA).</p>
                            </div>
                            <div class="text-center">
                                <a href="#" class="link">www.airadvice.com</a>
                                <p class="text-sm m-0 font-weight-bold">2017 AirAdvice, Inc</p>
                                <p class="text-sm font-weight-bold text-uppercase">all rights reserved</p>
                            </div>
                        </aside>
                        <article class="content col-9 px-4">
                            <section class="content__graph">
                                <figure id="carbon-monoxide-chart" class="d-flex justify-content-center"></figure>
                            </section>
                            <section class="row p-4">
                                <div class="col mx-3 border-tl">
                                    <h2 class="content__title font-weight-bold text-uppercase">About Carbon Monoxide</h2>
                                    <p>
                                        Elevated carbon monoxide levels in the home are a cause for concern. They can occur due to source causes, home heating & cooling system issues, or both.
                                    </p>
                                    <p>
                                        Sources: Fireplaces, cooking, combustion appliances (water heater, gas dryer, stove), vehicles running in attached garage.
                                    </p>
                                    <p>
                                        Possible heating & cooling system issues: Cracked heat exchanger on furnace, leaking chimney or vent, inadequate exhausting of a combustion appliance (water heater, gas dryer, stove).
                                    </p>
                                </div>
                                <div class="col mx-3 border-tl">
                                    <h2 class="content__title font-weight-bold text-uppercase">Recommended Action</h2>
                                    <p>None -- no action necessary. For more information on indoor air quality, see: </p>
                                    <ul>
                                        <li>www.airadvice.com</li>
                                    </ul>
                                </div>
                            </section>
                        </article>
                    </section>
                </div>
                <div class="page__logo position-absolute bg-white">
                    <img src="images/logo.png" alt="Logo.png" srcset="">
                </div>
            </main>
        </div>

        <script src="https://d3js.org/d3.v4.js"></script>
        <script>
            const data = {!! $data !!};

            const colorAxisWidth = 30,
                margin = {
                    top: 30,
                    right: colorAxisWidth + 5,
                    bottom: 30,
                    left: 110
                },
                width = 700 - margin.left - margin.right,
                height = 400 - margin.top - margin.bottom,
                thresholds = {
                    acceptable: 5,
                    danger: 10,
                };
               

            const svg = d3.select('#carbon-monoxide-chart')
                .append('svg')
                    .attr('width', width + margin.left + margin.right)
                    .attr('height', height + margin.top + margin.bottom)
                .append('g')
                    .attr('transform', `translate(${margin.left}, ${margin.top})`);

                    const maxValue = d3.max(data, function(d) { return +d.value; });
                    const parseDate = d3.timeParse("%Y-%m-%d %H:%M:%S");
                    data.forEach(function (d) {
                        d.date = parseDate(d.date);
                    });
                    

                    function drawColorAxis (svg, float) {
                        const x = float === 'left' ? -colorAxisWidth : width,
                            y = 0,
                            acceptableHeight = thresholds.acceptable ?  height * thresholds.acceptable / maxValue : 0,
                            dangerHeight = thresholds.danger ? height * (1 - thresholds.danger / maxValue) : 0,
                            fontSize = thresholds.acceptable / maxValue > 0.2 ? '1rem' : '0.7rem',
                            colorAxis = svg.append('g').attr('transform', `translate(${x}, ${y})`);

                            const colors = [ 'rgb(255,0,0)', 'rgb(255,255,0)' ],
                                grad = svg.append('defs')
                                    .append('linearGradient')
                                        .attr('id', 'grad')
                                        .attr('x1', '0%')
                                        .attr('x2', '0%')
                                        .attr('y1', '60%')
                                        .attr('y2', '100%');
                    
                            grad.selectAll('stop')
                                .data(colors)
                                .enter()
                                .append('stop')
                                    .style('stop-color', function(d){ return d; })
                                    .attr('offset', function(d,i){
                                        return 100 * (i / (colors.length - 1)) + '%';
                                    });
                                
                            colorAxis.append('rect')
                                .attr('width', colorAxisWidth)
                                .attr('height', height)
                                .attr('stroke', '#666666')
                                .attr('fill-opacity', 0)
                                .attr('stroke-width', 5);
                    
                            colorAxis.append('rect')
                                .attr('width', colorAxisWidth)
                                .attr('height', dangerHeight)
                                .style('fill', '#7d0808');

                            colorAxis.append('rect')
                                .attr('width', colorAxisWidth)
                                .attr('height', height - dangerHeight - acceptableHeight)
                                .attr('transform', `translate(0, ${dangerHeight})`)
                                .style('fill', 'url(#grad)');
                    
                            colorAxis.append('rect')
                                .attr('transform', `translate(0, ${height - acceptableHeight})`)
                                .attr('width', colorAxisWidth)
                                .attr('height', acceptableHeight)
                                .style('fill', '#1d5929');
                    
                            colorAxis.append('text')
                                .text('Acceptable')
                                .attr('text-anchor', 'middle')
                                .attr('alignment-baseline', 'central')
                                .attr(
                                    'transform',
                                    [
                                        `rotate(${float === 'left' ? '-90' : '90'})`,
                                        `translate(
                                            ${
                                                float === 'left'
                                                ? -height * (1-thresholds.acceptable/2/maxValue)
                                                : height * (1-thresholds.acceptable/2/maxValue)
                                            },
                                            ${
                                                float === 'left'
                                                ? colorAxisWidth/2
                                                : -colorAxisWidth/2
                                            })`
                                    ])
                                .attr('font-size', fontSize)
                                .attr('fill', '#fff');
                    };

                    // x axis bottom
                    const xScaleBottom = d3.scaleTime()
                        .domain(d3.extent(data, function(d) { return new Date(d.date); }))
                        .range([ 0, width ]);

                    const xAxisBottom = d3.axisBottom(xScaleBottom)
                        .tickFormat(d3.timeFormat('%H'))
                        .ticks(d3.timeMonth.every(4));

                    svg.append('g')
                        .attr('transform', `translate(0, ${height})`)
                        .call(xAxisBottom);

                    // x axis top
                    const xScaleTop = d3.scaleTime()
                        .domain(d3.extent(data, function(d) { return d.date; }))
                        .range([ 0, width ]);

                    const xAxisTop = d3.axisTop(xScaleTop)
                        .tickFormat(d3.timeFormat('%Y'))
                        .ticks(d3.timeYear.every(1));

                    svg.append('g')
                        .call(xAxisTop);

                    // y axis left
                    const yScaleLeft = d3.scaleLinear()
                        .domain([0, d3.max(data, function(d) { return +d.value; })])
                        .range([ height, 0 ]);

                    svg.append('g')
                        .attr('transform', `translate(${-colorAxisWidth}, 0)`)
                        .call(d3.axisLeft(yScaleLeft))
                        .call(g => g.selectAll("line").remove())
                        .call(g => g.select(".domain").remove());

                    // Thresholds line
                    svg.append('line')
                        .style('stroke', '#56c856')
                        .attr('stroke-width', 3)
                            .attr('x1', 0)
                            .attr('y1', yScaleLeft(thresholds.acceptable))
                            .attr('x2', width)
                            .attr('y2', yScaleLeft(thresholds.acceptable));

                    // Color axis
                    drawColorAxis(svg, 'left');
                    drawColorAxis(svg, 'right');

                    // Chart grid
                    const xAxisBottomGrid = d3.axisBottom(xScaleBottom).tickSize(-height).tickFormat('').ticks(20);
                    const yAxisGrid = d3.axisLeft(yScaleLeft).tickSize(-width).tickFormat('').ticks(10);

                    svg.append('g')
                        .attr('class', 'x axis-grid')
                        .attr('transform', `translate(0, ${height})`)
                        .call(xAxisBottomGrid);
                    svg.append('g')
                        .attr('class', 'y axis-grid')
                        .call(yAxisGrid);

                    // Line
                    svg.append('path')
                        .datum(data)
                        .attr('fill', 'none')
                        .attr('stroke', '#933a38')
                        .attr('stroke-width', 1.5)
                        .attr('d', d3.line()
                            .x(function(d) { return xScaleBottom(d.date) })
                            .y(function(d) { return yScaleLeft(d.value) })
                        );

        </script>
        <!-- <script src="{{ public_path('js/carbon-monoxide-copy.js') }}"></script> -->
    </body>
</html>
