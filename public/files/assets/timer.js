(function() {
    console.log(`Script is activated at ${new Date().toString()}`)
    let el = document.getElementById('discount-timeout');

    if(el !== null)
    {
        let timerId = el.dataset.timerId;
        getConfig('https://discount-timeout.herokuapp.com/get/timer/' + getShopName() + '?timer=' + timerId, (response)=> {
            let today = new Date();
            let timeout = new Date().setDate(today.getDate() + 3);

            if(response.timeout !== null)
            {
                timeout = response.timeout;
            }
            // Call the tick method to start countdown
            // Pass the final date and time to this method as string
            tick(timeout);
        })
    }

    function getShopName()
    {
        let url = window.location.href;
        let urlSplits = url.split('.');
        return urlSplits[0].substr(8, urlSplits[0].length);
    }

    function compare(start, end)
    {
      let diff = end - start;
      return diff;
    }

    function stringToUnix(string)
    {
        let date = new Date(string);
        if(typeof date == 'object')
        {
             return parseInt((date.getTime()).toFixed(0))
        }
    }

    function msToTime(duration)
    {
        var milliseconds = parseInt((duration % 1000) / 100)
        var seconds = parseInt((duration / 1000) % 60)
        var minutes = parseInt((duration / (1000 * 60)) % 60)
        var hours = parseInt((duration / (1000 * 60 * 60)) % 24)
        var days = parseInt((duration / (1000 * 60 * 60 * 24)))

        return {
            days,
            hours,
            minutes,
            seconds,
            milliseconds
        }
    }

    function tick(timeout)
    {
        setInterval(function() {
            let today = new Date().toString();
            let diff = compare(stringToUnix(today), stringToUnix(timeout));
            let tick = render(msToTime(diff));
            el.innerHTML = tick;
        }, 1000, timeout)
    }

    function render(payload)
    {
        let minutes = (payload.minutes < 10) ? "0" + payload.minutes : payload.minutes;
        let seconds = (payload.seconds < 10) ? "0" + payload.seconds : payload.seconds;
        let timeSpams = [
            { label: 'days', value: payload.days },
            { label: 'hours', value: payload.hours },
            { label: 'minutes', value: minutes },
            { label: 'seconds', value: seconds }];

        let out = `<div class="note form-success" style="width:100%;display:flex;justify-content:center;text-align: center;">`;

        if(payload.days !== 0 && payload.hours !== 0 && payload.minutes !== 0 && payload.seconds !== 0)
        {
            for(let i = 0; i < timeSpams.length; i++)
            {
                out += `<div ${(i == (timeSpams.length - 1)) ? '' : 'style="margin-right: 10px;"'}>${timeSpams[i].value} ${timeSpams[i].label}</div>`;
            }
        } else {
            out += `This offer has expired. Stay close for more special offers!`;
        }
        out += `</div>`;

        return out;
    }

    function getConfig(url, callback)
    {
        let options = {
            method: 'GET',
            mode: 'cors',
            headers: { Accept: 'application/json' }
        }

        fetch(url, options).then((response)=> {
            return response.json();
        }).then((result)=> {
            callback(result)
        }).catch((err)=> {
            console.log(err);
        });
    }

})();
