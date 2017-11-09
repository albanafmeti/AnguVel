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
    console.log('1: Clicked Login.');
    this._auth.authenticate(this.userEmail, this.userPassword).subscribe(
      response => {
        console.log('2: response.createdTime = new Date().getTime();.');
        response.createdTime = new Date().getTime();
        console.log('3: Cookie.set(\'tokenObj\', JSON.stringify(response), 1);');
        Cookie.set('tokenObj', JSON.stringify(response), 1);
        console.log('4: this._router.navigateByUrl(\'/admin/dashboard\');');
        this._router.navigateByUrl('/admin/dashboard');
      },
      responseError => {
        this.errorMessage = responseError.error.message;
      }
    );
  }
}
