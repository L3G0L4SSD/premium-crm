import './bootstrap';
import toastr from 'toastr';
import Alpine from 'alpinejs';
import 'toastr/build/toastr.min.css';

window.Alpine = Alpine;
window.toastr = toastr;

Alpine.start();
