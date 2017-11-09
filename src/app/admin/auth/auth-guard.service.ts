import {Injectable} from '@angular/core';
import {Router, CanActivate} from '@angular/router';
import {AuthService} from './auth.service';

@Injectable()
export class AuthGuardService implements CanActivate {

  constructor(public router: Router) {
  }

  canActivate(): boolean {
    console.log('5: if (!AuthService.isUserAuthenticated()) {');
    if (!AuthService.isUserAuthenticated()) {
      console.log('6: this.router.navigate([\'admin/login\']);');
      this.router.navigate(['admin/login']);
      return false;
    }
    return true;
  }
}
