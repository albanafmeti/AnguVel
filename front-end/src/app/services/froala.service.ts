import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/of';
import {AuthService} from '../admin/auth/auth.service';
import {environment} from '../../environments/environment';

@Injectable()
export class FroalaService {
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

  deleteImage(path: string): Observable<any> {
    if (this.accessToken) {
      return this._http.post(environment.apiUrl + 'froala/image/delete', {
        path: path
      }, {
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
