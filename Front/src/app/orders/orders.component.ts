import { Component, OnInit } from '@angular/core';
import { OrdersService } from '../services/orders.service';
import { CustomersService } from '../services/customers.service';

@Component({
  selector: 'app-orders',
  templateUrl: './orders.component.html',
  styleUrls: ['./orders.component.scss']
})
export class OrdersComponent implements OnInit {

  orders:any = [];
  customers:any = [];
  search:any = {
    from_date: null,
    to_date: null,
    customer_id: 0,
    page: 1
  }
  loading:boolean = true;

  constructor(
    private ordersService:OrdersService,
    private customersService:CustomersService
  ) { }

  ngOnInit(): void {
    this.getOrders();
    this.getCustomers();
  }
  getOrders() {
    this.orders = [];
    this.loading = true;
    this.ordersService.getOrders(this.search).subscribe(res=>{
      this.orders = res;
      this.loading = false;
    })
  }
  getCustomers() {
    this.customersService.getCustomers().subscribe(res=>{
      this.customers = res;
    })
  }
  paginate(e:any) {
    this.search.page = e.pageIndex + 1
    this.getOrders();
  }

}
