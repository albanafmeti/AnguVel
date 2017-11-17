import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {Observable} from 'rxjs/Observable';
import 'rxjs/add/observable/of';
import {environment} from '../../environments/environment';

@Injectable()
export class FbAppsService {

  constructor(private _http: HttpClient) {
  }

  appResult_1000(data: any): Observable<any> {
    return this._http.post(environment.apiUrl + 'fb/apps/1000/result', data);
  }
}
