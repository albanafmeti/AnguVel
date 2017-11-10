import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {Router} from '@angular/router';

@Component({
  selector: 'app-search-blog',
  templateUrl: './search-blog.component.html'
})
export class SearchBlogComponent implements OnInit {

  @Input() redirect: string = null;
  @Output() notifySearch: EventEmitter<string> = new EventEmitter<string>();
  keywords: string;

  constructor(private router: Router) {
  }

  ngOnInit() {
  }

  public search() {

    if (this.redirect) {
      this.router.navigateByUrl(this.redirect + '?q=' + this.keywords);
    }

    this.notifySearch.emit(this.keywords);
  }

  public enterSearch(event) {
    if (event.keyCode === 13) {
      this.search();
    }
  }

}
