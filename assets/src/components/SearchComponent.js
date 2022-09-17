import React, { useEffect } from "react";
import { connect } from "react-redux";
import * as prestationsActions from "../store/actions/prestations";
import { NativeEventSource, EventSourcePolyfill } from "event-source-polyfill";
const BaseURL = "http://127.0.0.1:8741/api/prestations/{id}";

const SearchComponent = ({ prestationEventListener }) => {
	useEffect(() => {
		const url = new URL(
			"http://localhost:8001/.well-known/mercure",
			//"https://v2uedk.stackhero-network.com/.well-known/mercure",
			window.origin
		);
		url.searchParams.append(
			"topic",
			"http://127.0.0.1:8741/api/prestations/{id}"
		);
		const eventSource = new EventSourcePolyfill(url, {
			headers: {
				Authorization:
					//"Bearer eyJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InB1Ymxpc2giOlsiKiJdLCJzdWJzY3JpYmUiOlsiKiJdfX0.4u95TXmEuZKdKmGjf95Sujle1vCOWBSdOeV-QFLPPBs",
					"Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyIqIl0sInB1Ymxpc2giOlsiKiJdfX0.uFoaLWui1L4dX06SV_HoUyh3s3FMGNVkfpvjEpwxhdc",
			},
		});
		eventSource.onmessage = (e) => {
			prestationEventListener(JSON.parse(e.data));
		}; // do something with the payload

		return () => {
			eventSource.close();
		};
	}, []);
	return <div>SearchComponent test mercure</div>;
};

export default connect(null, prestationsActions)(SearchComponent);
