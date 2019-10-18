import {Component, OnInit} from '@angular/core';
import * as CanvasJS from '../../../assets/vendor/canvasjs/2.3.2/canvasjs.min';
import {BccrService} from '../../services/bccr.service';
import * as moment from 'moment';

@Component({
  selector: 'app-main',
  templateUrl: './main.component.html',
  styleUrls: ['./main.component.css']
})
export class MainComponent implements OnInit {


  public loading = false;

  public currentTime;
  public startDate;
  public endDate;

  public resultsBuy = null;
  public resultsSell = null;


  constructor(private bccrService: BccrService) {
  }

  ngOnInit() {
    this.setUpVariables();
    this.getExchangeRate();
    // this.renderChart();
  }

  setUpVariables() {
    this.currentTime = moment();
    this.endDate = moment();
    this.startDate = moment().subtract(90, 'days');
  }


  getExchangeRate() {
    this.loading = true;
    this.bccrService.getExchangeRateAll(
      this.startDate.format('DD/MM/YYYY'), this.endDate.format('DD/MM/YYYY')
    ).subscribe((res: any) => {
      if (typeof res.information.buy !== 'undefined' && typeof res.information.sell !== 'undefined') {
        console.log('Information exists');
        this.resultsBuy = res.information.buy;
        this.resultsSell = res.information.sell;
        this.renderChart();
        this.loading = false;
      }
    }, error => {
      this.getExchangeRate();
    });
  }

  renderChart() {

    const buyPoints = [];
    const sellPoints = [];

    let counter = 0;
    let total = 0;
    // console.log( 'this.resultsBuy',  this.resultsBuy );

    // tslint:disable-next-line:prefer-for-of
    for (let i = 0; i < this.resultsBuy.length; i++) {
      // console.log( 'Single buy point' ,this.resultsBuy[i] );
      total += this.resultsBuy[i].value;
      counter++;
      buyPoints.push({
        x: new Date(this.resultsBuy[i].year, this.resultsBuy[i].month - 1, this.resultsBuy[i].day),
        y: this.resultsBuy[i].value
      });
    }

    for (let i = 0; i < this.resultsSell.length; i++) {
      // console.log( 'Single buy point' ,this.resultsBuy[i] );
      total += this.resultsSell[i].value;
      counter++;
      sellPoints.push({
        x: new Date(this.resultsBuy[i].year, this.resultsBuy[i].month - 1, this.resultsBuy[i].day),
        y: this.resultsSell[i].value
      });
    }


    const average = total / counter;

    // console.log('buyPoints', buyPoints);
    // console.log('sellPoints', sellPoints);

    const chart = new CanvasJS.Chart('chartContainer', {
      animationEnabled: true,
      exportEnabled: true,
      title: {
        text: 'Histórico de Tipo de Cambio del Dólar (CR Colones)'
      },
      axisX: {
        valueFormatString: 'DD/MM/Y'
      },
      axisY: {
        includeZero: false,
        stripLines: [{
          value: average,
          label: 'Media ' + parseFloat(average.toString()).toFixed(2)
        }]
      },
      data: [
        {
          name: 'Histórico de Precio de Compra',
          type: 'spline',
          showInLegend: true,
          dataPoints: buyPoints
        },
        {
          name: 'Histórico de Precio de Venta',
          type: 'spline',
          showInLegend: true,
          dataPoints: sellPoints
        }
      ]
    });
    chart.render();
  }

}
