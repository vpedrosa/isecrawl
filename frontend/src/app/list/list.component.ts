import {Component, OnInit} from '@angular/core';
import {Http} from "@angular/http";

@Component({
    selector: 'app-list',
    templateUrl: './list.component.html',
    styleUrls: ['./list.component.css']
})
export class ListComponent implements OnInit {

    private page_links:string [] = [];
    private current_page:number = 1;
    private total_pages:number = 0;
    private may_show_last_arrow:boolean = this.current_page<this.total_pages;
    private may_show_first_arrow:boolean = this.current_page != 1;
    private analized:number = 0;
    private valid_servers:number = 0;
    private vulnerabilities_found:number = 0;
    constructor(private http:Http) {
    }

    ngOnInit() {

    }

}
