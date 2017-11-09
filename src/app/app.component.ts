import {Component, OnInit} from '@angular/core';
import {PostService} from './services/post.service';
import {CategoryService} from './services/category.service';
import {EmitterService} from './services/emitter.service';
import {NavigationEnd, Router} from '@angular/router';

@Component({
  selector: 'app-root',
  template: `
    <div>
      <ng2-slim-loading-bar col></ng2-slim-loading-bar>
      <simple-notifications [options]="notificationOptions"></simple-notifications>
      <app-header></app-header>
      <router-outlet></router-outlet>
      <app-footer></app-footer>
    </div>
  `,
  providers: [PostService, CategoryService, EmitterService]
})
export class AppComponent implements OnInit {
  title = 'app';

  notificationOptions: any = {
    timeOut: 3000
  };

  constructor(private router: Router) {
  }

  ngOnInit() {
    this.router.events.subscribe((evt) => {
      if (!(evt instanceof NavigationEnd)) {
        return;
      }
      window.scrollTo(0, 0);
    });
  }
}
