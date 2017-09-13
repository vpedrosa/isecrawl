import {BrowserModule} from '@angular/platform-browser';
import {NgModule} from '@angular/core';
import {FormsModule} from '@angular/forms';
import {HttpModule} from '@angular/http';

import {AppComponent} from './app.component';
import {RouterModule, Routes} from "@angular/router";
import {HomeComponent} from './home/home.component';
import { ShowComponent } from './show/show.component';
import { ListComponent } from './list/list.component';
import { NotFoundComponent } from './not-found/not-found.component';
import { MaterializeModule } from 'angular2-materialize';

const appRoutes: Routes = [
    {path: '', component: HomeComponent},
    {path: 'analizados', component: ListComponent},
    {path: 'analizados/1', component: ShowComponent},
    {path: '**', component: NotFoundComponent},
];

@NgModule({
    declarations: [
        AppComponent,
        HomeComponent,
        ShowComponent,
        ListComponent,
        NotFoundComponent
    ],
    imports: [
        BrowserModule,
        FormsModule,
        HttpModule,
        RouterModule.forRoot(appRoutes)
    ],
    providers: [],
    bootstrap: [AppComponent]
})
export class AppModule {
}
