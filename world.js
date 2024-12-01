document.addEventListener("DOMContentLoaded", () => {
  const lookupButton = document.getElementById("lookup");
  const lookupCitiesButton = document.getElementById("lookup-cities");
  const resultDiv = document.getElementById("result");
  const countryInput = document.getElementById("country");

  const fetchData = (lookupType) => {
    const country = countryInput.value.trim();
    const url = `world.php?country=${encodeURIComponent(country)}&lookup=${lookupType}`;

    fetch(url)
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
      })
      .then(data => {
        resultDiv.innerHTML = data || "<p>No results found.</p>";
      })
      .catch(error => {
        console.error('Error:', error);
        resultDiv.innerHTML = "<p>There was an error fetching the data.</p>";
      });
  };

  lookupButton.addEventListener("click", () => fetchData("countries"));
  lookupCitiesButton.addEventListener("click", () => fetchData("cities"));
});
