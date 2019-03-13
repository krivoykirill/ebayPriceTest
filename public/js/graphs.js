window.addEventListener('DOMContentLoaded', initializeGraphs, false);
function initializeGraphs(){
    if (typeof data === 'undefined') {
        return;
    }
    initializeSums();

    var context=document.getElementById('medianeWeekly').getContext('2d');
    var source = data.data.aggregates.medianes.weekly;
    createTimeSeriesGraph(context,source,'Weekly average prices');

    var context=document.getElementById('medianeDaily').getContext('2d');
    var source = data.data.aggregates.medianes.daily;
    createTimeSeriesGraph(context,source,'Daily average prices');  

    var context=document.getElementById('medianeMonthly').getContext('2d');
    var source = data.data.aggregates.medianes.montlhy;
    createTimeSeriesGraph(context,source,'Monthly average prices');

    var context=document.getElementById('sumDaily').getContext('2d');
    var source = data.data.aggregates.sums.daily;
    createTimeSeriesSumGraph(context,source,'Total sold each day');

    var context=document.getElementById('sumWeekly').getContext('2d');
    var source = data.data.aggregates.sums.weekly;
    createTimeSeriesSumGraph(context,source,'Total sold each week');

    var context=document.getElementById('sumMonthly').getContext('2d');
    var source = data.data.aggregates.sums.monthly;
    createTimeSeriesSumGraph(context,source,'Total sold each month');

    filterVendorsAndPlebs();

}
function createTimeSeriesGraph(context,source,label){
    var labels=[];
    var chartData=[];
    for (var k in source){
        if (source.hasOwnProperty(k)) {
            date=new Date(k)
            labels.push(getDateFromISO(date));
            chartData.push(source[k]);
        }
    }
    var chart = new Chart(context,{
        type: 'line',
        // The data for our dataset
        data: {
            labels: labels,
            datasets: [{
                label: label,
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: chartData,
            }]
        },
        // Configuration options go here
        options: {}
    });
}
function createTimeSeriesSumGraph(context,source,label){
    var labels=[];
    var chartData=[];
    for (var k in source){
        if (source.hasOwnProperty(k)) {
            date=new Date(k)
            labels.push(getDateFromISO(date));
            chartData.push(source[k]);
        }
    }
    var chart = new Chart(context,{
        type: 'line',
        // The data for our dataset
        data: {
            labels: labels,
            datasets: [{
                label: label,
                backgroundColor: 'rgb(98, 124, 255)',
                borderColor: 'rgb(98, 124, 255)',
                data: chartData,
            }]
        },
        // Configuration options go here
        options: {}
    });
}

function getDateFromISO(date){
    year = date.getFullYear();
    month = date.getMonth()+1;
    dt = date.getDate();

    if (dt < 10) {
    dt = '0' + dt;
    }
    if (month < 10) {
    month = '0' + month;
    }
    return year+'-' + month + '-'+dt;
}
function initializeSums() {
    var day = document.getElementById("sumTotal");
    var sums = data.data.aggregates.sums;
    day.innerHTML="Total sold worth of <b>£ "+(sums.all_time)+"</b> since <b>"+data.data.items[data.data.items.length-1].end_time +"</b>, <br/> Total amount of items found: <b>"+data.data.items.length+"</b>";
}
function filterVendorsAndPlebs(){
    //chislo plebeev i chislo vendorov
    var sellers= data.data.sellers.seller_sales_count;
    var sellerList =0;
    var plebList=0;
    for (var k in sellers){
        if (sellers.hasOwnProperty(k)) {
            if (sellers[k]==1){
                plebList+=1;
            }
            else {
                sellerList+=sellers[k];
            }
        }
    }
    new Chart(document.getElementById("vendToPleb"), {
    type: 'pie',
    data: {
      labels: ["Sellers", "Buyers"],
      datasets: [{
        label: "Amount of deals between vendors and regular users",
        backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
        data: [sellerList,plebList]
      }]
    },
    options: {
      title: {
        display: true,
        text: 'Amount of deals between vendors and regular users within last 3 months'
      }
    }
});
}

