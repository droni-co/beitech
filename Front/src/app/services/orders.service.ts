import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class OrdersService {

  private apiUrl = '';

  constructor(private http: HttpClient) { }

  getOrders(search:any={}) {
    let query = '?';
    if(search.customer_id > 0) { query += 'customer_id='+search.customer_id+'&'}
    if(search.from_date != null) { 
      let from_date = new Date(search.from_date);
      let stFrom_date = from_date.toISOString().split('T')[0]
      query += 'from_date='+stFrom_date+'&'
    }
    if(search.to_date != null) { 
      let to_date = new Date(search.to_date);
      let stTo_date = to_date.toISOString().split('T')[0]
      query += 'to_date='+stTo_date
    }
    if(search.page != 0) {
      query += 'page='+search.page
    }
    return this.http.get(this.apiUrl+'/api/orders'+query);
  }
  createOrder(customer:any, products:Array<any>, delivery_address:string) {
    let data:any = {
      customer_id: customer.customer_id,
      products: products,
      delivery_address: delivery_address
    }
    return this.http.post(this.apiUrl+'/api/orders', data);
  }
}
