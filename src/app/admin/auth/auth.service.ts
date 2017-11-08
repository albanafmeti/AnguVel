import {Injectable} from '@angular/core';
import {Observable} from 'rxjs/Observable';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../../environments/environment';
import {Cookie} from 'ng2-cookies/ng2-cookies';

@Injectable()
export class AuthService {

  constructor(private _http: HttpClient) {
  }

  static isUserAuthenticated() {
    const tokenStr = Cookie.get('tokenObj');
    if (tokenStr) {
      const tokenObj = JSON.parse(tokenStr);
      const currentTime = new Date().getTime();
      const createdTokenTime = tokenObj.createdTime;

      return (currentTime - createdTokenTime <= 7200000); // in miliseconds
    }
    return false;
  }

  authenticate(email: string, password: string): Observable<any> {
    return this._http.post(environment.appUrl + 'oauth/token', {
      grant_type: 'password',
      client_id: environment.client_id,
      client_secret: environment.client_secret,
      username: email,
      password: password,
      scope: '*',
    });
  }

  accessToken() {
    const tokenStr = Cookie.get('tokenObj');
    if (tokenStr) {
      const tokenObj = JSON.parse(tokenStr);
      return tokenObj.access_token;
    }
    return null;
  }
}
