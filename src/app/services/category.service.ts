import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/of';
import {URLSearchParams} from '@angular/http';
import {AuthService} from '../admin/auth/auth.service';
import {environment} from '../../environments/environment';

@Injectable()
export class CategoryService {
  accessToken: string;
  headers: any;

  constructor(private _http: HttpClient,
              private _auth: AuthService) {
    this.accessToken = this._auth.accessToken();

    this.headers = new HttpHeaders({
      Accept: 'application/json',
      Authorization: 'Bearer ' + this.accessToken,
    });
  }

  getCategories(options?: any): Observable<any> {

    options = options ? options : {};

    if (options.url) {
      return this._http.get(options.url);
    }

    const params = new URLSearchParams();
    if (options.page) {
      params.append('page', String(options.page));
    }

    if (options.perPage) {
      params.append('perPage', String(options.perPage));
    }

    if (options.query) {
      params.append('q', options.query);
    }

    const fullQuery = params.toString();
    return this._http.get(environment.apiUrl + 'categories' + ( fullQuery ? ('?' + fullQuery) : ''));
  }

  getCategory(slug: string): Observable<any> {
    return this._http.get(environment.apiUrl + 'categories/' + slug);
  }

  postsByCategory(slug: string, options?: any): Observable<any> {

    options = options ? options : {};

    if (options.url) {
      return this._http.get(options.url);
    }

    const params = new URLSearchParams();
    if (options.page) {
      params.append('page', String(options.page));
    }

    if (options.perPage) {
      params.append('perPage', String(options.perPage));
    }

    if (options.query) {
      params.append('q', options.query);
    }

    const fullQuery = params.toString();
    return this._http.get(environment.apiUrl + 'categories/' + slug + '/posts' + ( fullQuery ? ('?' + fullQuery) : ''));
  }

  storeCategory(data: any): Observable<any> {

    if (this.accessToken) {
      return this._http.post(environment.apiUrl + 'categories/store', data, {
        headers: this.headers
      });

    } else {
      return Observable.of({
        success: false,
        message: 'Permission denied.'
      });
    }
  }

  deleteCategory(category: any): Observable<any> {
    if (this.accessToken) {
      return this._http.delete(environment.apiUrl + 'categories/' + category.slug + '/delete', {
        headers: this.headers
      });

    } else {
      return Observable.of({
        success: false,
        message: 'Permission denied.'
      });
    }
  }

  updateCategory(slug: string, data: any): Observable<any> {

    if (this.accessToken) {
      return this._http.put(environment.apiUrl + 'categories/' + slug + '/update', data, {
        headers: this.headers
      });

    } else {
      return Observable.of({
        success: false,
        message: 'Permission denied.'
      });
    }
  }
}
