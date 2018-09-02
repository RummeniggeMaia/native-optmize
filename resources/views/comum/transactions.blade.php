<div class="row animation-fadeInQuick">
    <div class="col-sm-6 col-lg-3">
        <a href="#" class="widget widget-hover-effect1">
            <div class="widget-simple">
                <div class="widget-icon pull-left themed-background-default animation-fadeIn">
                    <i class="gi gi-wallet"></i>
                </div>
                <h3 class="widget-content text-right animation-pullDown">
                    @if(Auth::user()->hasAnyRole(['admin', 'adver']))
                        <strong>R$ {{ number_format(Auth::user()->revenue_adv, 2) }}</strong>
                    @else
                        <strong>R$ {{ number_format(Auth::user()->revenue, 2) }}</strong>
                    @endif
                    <small>Disponível</small>
                </h3>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="#" class="widget widget-hover-effect1">
            <div class="widget-simple">
                <div class="widget-icon pull-left themed-background-default animation-fadeIn">
                    <i class="gi gi-undo"></i>
                </div>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>R$ {{ number_format(Auth::user()->payments()->where('status', 1)->sum('paid_value'), 2) }}</strong>
                    <small>Transferido</small>
                </h3>
            </div>
        </a>
    </div>
    @php $waiting = Auth::user()->payments()->where('status', 2)->sum('brute_value') @endphp
    <div class="col-sm-6 col-lg-3">
        <a href="#" class="widget widget-hover-effect1">
            <div class="widget-simple">
                <div class="widget-icon pull-left themed-background-default animation-fadeIn">
                    <i class="gi gi-clock"></i>
                </div>
                <h3 class="widget-content text-right animation-pullDown">
                    <strong>R$ {{ number_format($waiting, 2) }}</strong>
                    <small>Aguardando</small>
                </h3>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="#" class="widget widget-hover-effect1">
            <div class="widget-simple">
                <div class="widget-icon pull-left themed-background-default animation-fadeIn">
                    <i class="gi gi-money"></i>
                </div>
                <h3 class="widget-content text-right animation-pullDown">
                    @if(Auth::user()->hasAnyRole(['admin', 'adver']))
                        <strong>R$ {{ number_format(Auth::user()->revenue_adv + $waiting, 2) }}</strong>
                    @else
                        <strong>R$ {{ number_format(Auth::user()->revenue + $waiting, 2) }}</strong>
                    @endif
                    
                    <small>Total</small>
                </h3>
            </div>
        </a>
    </div>
</div>