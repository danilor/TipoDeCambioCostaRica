import {Component, OnInit} from '@angular/core';

@Component({
  selector: 'app-loading',
  templateUrl: './loading.component.html',
  styleUrls: ['./loading.component.css']
})
export class LoadingComponent implements OnInit {

  public messages = [
    'Cargando. Por favor espere',
    'La paciencia es una virtud',
    'Recuperando información',
    'Pronto la información estará lista para visualizarse',
    'Cargando',
    'Por favor espere',
    'Recuperando Información'
  ];

  public currentMessage = 0;
  private timed = 4000;

  constructor() {
  }

  ngOnInit() {
    this.setUpTimer();
  }

  setUpTimer() {
    setInterval(() => {
      this.currentMessage++;
      if (this.currentMessage >= this.messages.length) {
        this.currentMessage = 0;
      }
    }, this.timed);
  }


}
