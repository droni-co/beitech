import { Component, OnInit } from '@angular/core';
import { OrdersService } from '../services/orders.service';
import { CustomersService } from '../services/customers.service';

@Component({
  selector: 'app-create-order',
  templateUrl: './create-order.component.html',
  styleUrls: ['./create-order.component.scss']
})
export class CreateOrderComponent implements OnInit {

  customers:any = [];
  orderCustomerId:number = 0;
  orderProducts:Array<any> = [];
  delivery_address:string = '';
  actualCustomer:any = {};
  inBagProducts:number = 0;
  loading:boolean = false;
  saved:any = {};

  constructor(
    private ordersService:OrdersService,
    private customersService:CustomersService
  ) { }

  ngOnInit(): void {
    this.getCustomers();
  }

  getCustomers() {
    this.customersService.getCustomers().subscribe(res=>{
      this.customers = res;
    })
  }
  loadCustomer() {
    this.loading = true;
    this.actualCustomer = {};
    this.orderProducts = [];
    this.customersService.getCustomer(this.orderCustomerId).subscribe(res=>{
      this.actualCustomer = res;
      this.loading = false;
      this.saved = {};
    })
  }
  addProduct(product:any) {
    let addNew:boolean = true;
    this.orderProducts.forEach(e=> {
      if(e.product_id == product.product_id) {
        e.quantity++;
        addNew = false;
      }
    })
    if(addNew) {
      this.orderProducts.push({
        product_id: product.product_id,
        quantity: 1,
        name: product.name,
        price: product.price
      })
    }
    this.calculateInBag()
  }
  removeProduct(product_id:number) {
    this.orderProducts.forEach((e,i,o)=> {
      if(e.product_id == product_id) {
        e.quantity--;
      }
      if(e.quantity < 1) {
        o.splice(i, 1);
      }
      this.calculateInBag()
    })
  }
  calculateInBag() {
    this.inBagProducts = 0;
    this.orderProducts.forEach(e=>{
      this.inBagProducts += e.quantity; 
    })
  }
  storeOrder() {
    let passData:any = {
      customer: this.actualCustomer,
      products: this.orderProducts,
      delivery_address: this.delivery_address
    }
    this.loading = true;
    this.actualCustomer = {};
    this.orderProducts = [];
    this.ordersService.createOrder(passData.customer, passData.products, passData.delivery_address).subscribe(res=>{
      this.saved = res;
      this.loading = false;
    })
  }

}
