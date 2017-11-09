import {Component, OnInit} from '@angular/core';
import {AuthService} from './auth.service';
import {Router} from '@angular/router';
import {CookieService} from 'ngx-cookie';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html'
})
export class LoginComponent implements OnInit {

  userEmail: string;
  userPassword: string;

  errorMessage: string;

  constructor(private _auth: AuthService,
              private _router: Router,
              private _cookie: CookieService) {
  }

  ngOnInit() {
  }

  public login() {
    this._auth.authenticate(this.userEmail, this.userPassword).subscribe(
      response => {
        response.createdTime = new Date().getTime();
        this._cookie.put('tokenObj', JSON.stringify(response));
        this._router.navigateByUrl('/admin/dashboard');
      },
      responseError => {
        this.errorMessage = responseError.error.message;
      }
    );
  }
}
