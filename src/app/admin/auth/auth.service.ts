import {Injectable} from '@angular/core';
import {Observable} from 'rxjs/Observable';
import {HttpClient} from '@angular/common/http';
import {environment} from '../../../environments/environment';
import {CookieService} from 'ngx-cookie';

@Injectable()
export class AuthService {

  constructor(private _http: HttpClient,
              private _cookie: CookieService) {
  }

  isUserAuthenticated() {
    const tokenStr = this._cookie.get('tokenObj');
    if (tokenStr) {
      const tokenObj = JSON.parse(tokenStr);
      const currentTime = new Date().getTime();
      const createdTokenTime = tokenObj.createdTime;

      return (currentTime - createdTokenTime <= 7200000); // in miliseconds
    }
    return false;
  }

  accessToken() {
    const tokenStr = this._cookie.get('tokenObj');
    if (tokenStr) {
      const tokenObj = JSON.parse(tokenStr);
      return tokenObj.access_token;
    }
    return null;
  }

  logout() {
    this._http.get(environment.apiUrl + 'auth/logout').subscribe(response => console.log(response));
    this._cookie.remove('tokenObj');
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
}
