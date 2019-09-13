import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { MainComponent } from './components/main/main.component';
import {HttpClientModule} from '@angular/common/http';
import { LoadingComponent } from './components/loading/loading.component';
import { ActualValuesComponent } from './components/actual-values/actual-values.component';

@NgModule({
  declarations: [
    AppComponent,
    MainComponent,
    LoadingComponent,
    ActualValuesComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
