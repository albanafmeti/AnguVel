import {Component, OnInit} from '@angular/core';
import {AuthService} from './auth.service';
import {Cookie} from 'ng2-cookies/ng2-cookies';
import {Router} from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html'
})
export class LoginComponent implements OnInit {

  userEmail: string;
  userPassword: string;

  errorMessage: string;

  constructor(private _auth: AuthService, private _router: Router) {
  }

  ngOnInit() {
  }

  public login() {
    this._auth.authenticate(this.userEmail, this.userPassword).subscribe(
      response => {
        response.createdTime = new Date().getTime();
        Cookie.set('tokenObj', JSON.stringify(response), 1);
        this._router.navigateByUrl('/admin/dashboard');
      },
      responseError => {
        this.errorMessage = responseError.error.message;
      }
    );
  }
}
