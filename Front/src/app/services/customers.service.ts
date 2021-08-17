import { Injectable } from '@angular/core';
import { HttpClient } from "@angular/common/http";


@Injectable({
  providedIn: 'root'
})
export class CustomersService {

  private apiUrl = '';

  constructor(private http: HttpClient) { }

  getCustomers() {
    return this.http.get(this.apiUrl+'/api/customers');
  }
  getCustomer(id=0) {
    return this.http.get(this.apiUrl+'/api/customers/'+id);
  }
}
