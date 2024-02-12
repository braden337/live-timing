<script>
  let race = null;

  async function getRace() {
    let res = await fetch('/results.php');
    let xml = await res.text();

    const domparser = new DOMParser();
    const doc = domparser.parseFromString(xml, 'text/xml');

    let riders = [...doc.querySelectorAll('B')].map(function (rider) {
      return {
        position: Number(rider.getAttribute('A')),
        number: Number(rider.getAttribute('N')),
        name: rider.getAttribute('F').slice(3),
        lastTime: rider.getAttribute('LL'),
        bestTime: rider.getAttribute('BL'),
        bestLap: rider.getAttribute('IN'),
        gap: rider.getAttribute('G'),
        lead: rider.getAttribute('D'),
        brand: rider.getAttribute('V').slice(0, 3).toLowerCase(),
        town: rider.getAttribute('T'),
      };
    });

    let results = doc.querySelector('A');

    let className = results.getAttribute('S');

    race = {
      info: {
        track: results.getAttribute('T'),
        event: results.getAttribute('E'),
        class: className,
        qualifying: className.indexOf('Qualifying') != -1,
        lap: results.getAttribute('R'),
      },
      riders,
    };
  }

  getRace();

  setInterval(getRace, 5000);

  let settings = {
    color: true,
    gap: true,
    last: true,
  };
</script>

<header>
  {#if race}
    {race.info.track}
  {/if}
</header>

<main>
  <table>
    <thead>
      <tr>
        <th class="pos">P</th>
        <th class="rider" on:click={() => (settings.color = !settings.color)}
          >Rider</th
        >
        <th on:click={() => (settings.gap = !settings.gap)} class="diff"
          >{settings.gap ? 'Gap' : 'Lead'}</th
        >
        <th on:click={() => (settings.last = !settings.last)} class="time"
          >{settings.last ? 'Last' : 'Best'}
          {#if !settings.last}<sub> in</sub>{/if}</th
        >
      </tr>
    </thead>
    {#if race}
      <tbody>
        {#each race.riders as rider (rider.number)}
          <tr>
            <td class="pos">{rider.position}</td>
            <td class="rider {settings.color ? rider.brand : ''}"
              >{rider.name} <span class="rider-number">{rider.number}</span></td
            >
            <td class="diff">{settings.gap ? rider.gap : rider.lead}</td>
            <td class="time"
              >{settings.last ? rider.lastTime : rider.bestTime}
              {#if !settings.last}<sub> {rider.bestLap}</sub>{/if}</td
            >
          </tr>
        {/each}
      </tbody>
    {/if}
  </table>
</main>

<footer>
  {#if race}
    {race.info.class} (Lap {race.info.lap})
  {/if}
</footer>
