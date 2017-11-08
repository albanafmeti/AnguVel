import {Injectable} from '@angular/core';
import {Router, CanActivate} from '@angular/router';
import {AuthService} from './auth.service';

@Injectable()
export class AuthGuardService implements CanActivate {

  constructor(public router: Router) {
  }

  canActivate(): boolean {
    if (!AuthService.isUserAuthenticated()) {
      this.router.navigate(['admin/login']);
      return false;
    }
    return true;
  }
}
