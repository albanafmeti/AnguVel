import {Component, OnInit} from '@angular/core';
import {Title} from '@angular/platform-browser';
import {EmitterService} from '../services/emitter.service';
import {Router} from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html'
})
export class HomeComponent implements OnInit {

  constructor(private router: Router, private titleService: Title) {
    this.titleService.setTitle('Te Rejat | Kliko dhe Informohu');
  }

  ngOnInit() {
    EmitterService.get('SEARCH_BLOG').subscribe(keywords => {
      this.router.navigateByUrl('/blog?q=' + keywords);
    });
  }
}
