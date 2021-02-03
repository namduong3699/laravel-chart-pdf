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
        acceptable: 4000,
        danger: 17000,
    };

const svg = d3.select('#carbon-monoxide-chart')
    .append('svg')
        .attr('width', width + margin.left + margin.right)
        .attr('height', height + margin.top + margin.bottom)
    .append('g')
        .attr('transform', `translate(${margin.left}, ${margin.top})`);

d3.csv('https://raw.githubusercontent.com/namduong3699/laravel-chart-pdf/temp/public/data/date_value.csv',
    function(data){
        return { date : d3.timeParse('%Y-%m-%d')(data.date), value : data.value }
    },

    function(data) {
        const maxValue = d3.max(data, function(d) { return +d.value; });

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
            .domain(d3.extent(data, function(d) { return d.date; }))
            .range([ 0, width ]);

        const xAxisBottom = d3.axisBottom(xScaleBottom)
            .tickFormat(d3.timeFormat('%b'))
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
            )
});
