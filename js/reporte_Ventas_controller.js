$(document).ready(function(){
  obtenerDatos();
  obtenerDatosMensuales();
  obtenerDatosSemanal();
//   obtenerDatosPRUEBA();
  function obtenerDatos(){
    let labels = [], datos = [];
    const $grafica = document.querySelector("#grafica");
    $.post('php/reporte_ventas_controller.php', {accion: 'ventaDiaria'}, function (response){
      // console.log(response);
      let productos = JSON.parse(response);
      // productos.forEach(producto => {
      for (let i = productos.length - 1; i >= 0 ; i--){
        labels.push(productos[i]['fecha']);
        datos.push(productos[i]['total']);
      }
      // });
      new Chart($grafica, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
              label: "Venta",
              data: datos,
              borderColor: 'rgba(211,93,110, 1)',
            //   backgroundColor: '#3EEAC0',
          }]
        },
        options: {
          responsive: true,
          maintainAspectrRatio: false,
          title:{
            display: true,
            text: 'VENTAS POR DIA',
            fontSize: 30,
            padding: 30,
            fontColor: '#12619c'
          },
          legend:{
            position: 'bottom',
            labels: {
              padding: 20,
              boxWidth: 15,
              FontFamyly: 'system-ui',
              fontColor: 'black',
            }
          },
          layout:{
            padding:{
              right: 25,
              left: 25,
              top: 0,
            }
          },
          tooltips:{
            backgroundColor: '#0584f6',
            titleFontSize: 20,
            xPadding: 20,
            yPaddin:20,
            bodyFontSize: 15,
            bodySapcing: 10,
            mode: 'x',
          },
          elements:{
            line: {
              borderWidth: 4,
              fill: false,
            },
            point:{
              radius: 6,
              borderWidth: 2,
              backgroundColor: 'white',
              hoverRadius: 8,
              hoverBorderWhidth: 2,
            }
          },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }],
          },
        }
      });
    
    });
  }
  function obtenerDatosMensuales(){
    let labels = [], datos = [];
    let meses = ['Enero', 'Febrero', 'Marzo', 'Abril','Mayo','Junio','Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    const $grafica = document.querySelector("#prueba");
    $.post('php/reporte_ventas_controller.php', {accion: 'ventaMensual'}, function (response){
    //   console.log(response);
      let productos = JSON.parse(response);
      for (let i = 0; i <= productos.length - 1; i++){
        labels.push(meses[productos[i]['mes']-1]);
        datos.push(productos[i]['total']);
      }
      new Chart($grafica, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
              label: "Venta",
              data: datos,
              borderColor: 'rgba(21,3,110, 1)',
            //   backgroundColor: '#E51A1A',
          }]
        },
        options: {
          title:{
            display: true,
            text: 'VENTAS POR MES',
            fontSize: 30,
            padding: 30,
            fontColor: '#12619c'
          },
          legend:{
            position: 'bottom',
            labels: {
              padding: 20,
              boxWidth: 15,
              FontFamyly: 'system-ui',
              fontColor: 'black',
            }
          },
          layout:{
            padding:{
              right: 25,
              left: 25,
              top: 0,
            }
          },
          tooltips:{
            backgroundColor: '#0584f6',
            titleFontSize: 20,
            xPadding: 20,
            yPaddin:20,
            bodyFontSize: 15,
            bodySapcing: 10,
            mode: 'x',
          },
          elements:{
            line: {
              borderWidth: 4,
              fill: false,
            },
            point:{
              radius: 6,
              borderWidth: 2,
              backgroundColor: 'white',
              hoverRadius: 8,
              hoverBorderWhidth: 2,
            }
          },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }],
          },
        }
      });
    });
  }
  function obtenerDatosSemanal(){
    let labels = [], datos = [];
    const $grafica = document.querySelector("#semanal");
    $.post('php/reporte_ventas_controller.php', {accion: 'ventaSemana'}, function (response){
      // console.log(response);
      let productos = JSON.parse(response);
      productos.forEach(producto => {
        labels.push('Semana ' + producto['week_date']);
        datos.push(producto['week_total']);
      });
      new Chart($grafica, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
              label: "Venta",
              data: datos,
              borderColor: 'rgba(21,3,10, 1)',
            //   backgroundColor: '#C609A4',
          }]
        },
        options: {
          title:{
            display: true,
            text: 'VENTAS POR SEMANA',
            fontSize: 30,
            padding: 30,
            fontColor: '#12619c'
          },
          legend:{
            position: 'bottom',
            labels: {
              padding: 20,
              boxWidth: 15,
              FontFamyly: 'system-ui',
              fontColor: 'black',
            }
          },
          layout:{
            padding:{
              right: 25,
              left: 25,
              top: 0,
            }
          },
          tooltips:{
            backgroundColor: '#0584f6',
            titleFontSize: 20,
            xPadding: 20,
            yPaddin:20,
            bodyFontSize: 15,
            bodySapcing: 10,
            mode: 'x',
          },
          elements:{
            line: {
              borderWidth: 4,
              fill: false,
            },
            point:{
              radius: 6,
              borderWidth: 2,
              backgroundColor: 'white',
              hoverRadius: 8,
              hoverBorderWhidth: 2,
            }
          },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }],
          },
        }
      });
    });
  }
  function RegrecionLineal(){
    let labels = [], datos = [];
    const $grafica = document.querySelector("#grafica");
    $.post('php/reporte_ventas_controller.php', {accion: 'ventaDiaria'}, function (response){
      // console.log(response);
      let productos = JSON.parse(response);
      // productos.forEach(producto => {
      for (let i = productos.length - 1; i >= 0 ; i--){
        labels.push(productos[i]['fecha']);
        datos.push(productos[i]['total']);
      }
      // });
      new Chart($grafica, {
        type: 'scatter',
        data: {
          // labels-: labels,
          datasets: [{
            label: "Datos",
            data: [{
              x: 4,
              y: 44
            }, {
              x: 3,
              y: 29
            }, {
              x: 2,
              y: 15
            }, {
              x: 1,
              y: 10
            }, {
              x: 4,
              y: 39
            }, {
              x: 3,
              y: 33
            }, {
              x: 2,
              y: 22
            }, {
              x: 1,
              y: 7
            }, {
              x: 4,
              y: 42
            }, {
              x: 3,
              y: 27
            }, {
              x: 2,
              y: 34
            }, {
              x: 1.5,
              y: 17
            }, {
              x: 2.5,
              y: 25
            }, {
              x: 3.5,
              y: 36
            }, {
              x: 2.5,
              y: 28
            }, {
              x: 1,
              y: 11
            }, {
              x: 0,
              y: 1
            }],
          borderColor: 'black',
          backgroundColor: 'black'
          },{
            label: "Regecion Lineal",
            data: [{
              x: 0,
              y: 1
            }, {
              x: 1,
              y: 10
            }, {
              x: 2,
              y: 20
            }, {
              x: 3,
              y: 30
            }, {
              x: 4,
              y: 40
            }],
          borderColor: 'green',
          backgroundColor: 'transparent',
          type: 'line'
          }]
        }
        // options: {
        //   responsive: true,
        //   maintainAspectrRatio: false,
        //   title:{
        //     display: true,
        //     text: 'VENTAS POR DIA',
        //     fontSize: 30,
        //     padding: 30,
        //     fontColor: '#12619c'
        //   },
        //   legend:{
        //     position: 'bottom',
        //     labels: {
        //       padding: 20,
        //       boxWidth: 15,
        //       FontFamyly: 'system-ui',
        //       fontColor: 'black',
        //     }
        //   },
        //   layout:{
        //     padding:{
        //       right: 25,
        //       left: 25,
        //       top: 0,
        //     }
        //   },
        //   tooltips:{
        //     backgroundColor: '#0584f6',
        //     titleFontSize: 20,
        //     xPadding: 20,
        //     yPaddin:20,
        //     bodyFontSize: 15,
        //     bodySapcing: 10,
        //     mode: 'x',
        //   },
        //   elements:{
        //     line: {
        //       borderWidth: 4,
        //       fill: false,
        //     },
        //     point:{
        //       radius: 6,
        //       borderWidth: 2,
        //       backgroundColor: 'white',
        //       hoverRadius: 8,
        //       hoverBorderWhidth: 2,
        //     }
        //   },
        //   scales: {
        //     yAxes: [{
        //       ticks: {
        //         beginAtZero: true
        //       }
        //     }],
        //   },
        // }
      });
    
    });
  }
});