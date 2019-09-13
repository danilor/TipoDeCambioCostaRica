import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';

import {environment} from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class BccrService {

  private tcName = 'dmm';

  // private urlBase = 'http://indicadoreseconomicos.bccr.fi.cr/indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/ObtenerIndicadoresEconomicosXML?tcIndicador=[$id]&tcFechaInicio=[$startDate]&tcFechaFinal=[$endDate]&tcNombre=[$tcName]&tnSubNiveles=N';

  private urlBase = environment.exchangeUrl;

  constructor(private http: HttpClient) {
    this.urlBase = this.urlBase + '?tcFechaInicio=[$startDate]&tcFechaFinal=[$endDate]&tcNombre=[$tcName]';
  }


  generateUrl(startDate, endDate) {
    let url = this.urlBase;
    url = url.replace('[$tcName]', this.tcName);
    url = url.replace('[$startDate]', startDate);
    url = url.replace('[$endDate]', endDate);
    return url;
  }

  /*getExchangeRateBuy(startDate, endDate) {
    const url = this.generateUrl(this.rateBuyId.toString(), startDate.toString(), endDate.toString());
    console.log('Working URL Buy', url);
    return this.http.get(url);
  }*/

  /*getExchangeRateSell(startDate, endDate) {
    const url = this.generateUrl(this.rateSellId.toString(), startDate.toString(), endDate.toString());
    console.log('Working URL Sell', url);
    return this.http.get(url);
  }*/

  getExchangeRateAll( startDate, endDate ){
    const url = this.generateUrl(startDate.toString(), endDate.toString());
    // console.log('Working URL', url);
    return this.http.get(url);
  }

}
