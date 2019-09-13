import {Component, Input, OnInit} from '@angular/core';

@Component({
  selector: 'app-actual-values',
  templateUrl: './actual-values.component.html',
  styleUrls: ['./actual-values.component.css']
})
export class ActualValuesComponent implements OnInit {


  @Input() buyValue = 0.00;
  @Input() sellValue = 0.00;



  constructor() { }

  ngOnInit() {
  }

}
