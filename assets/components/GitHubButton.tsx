import React from "react";

const GitHubButton: React.FC = () => {
  return <a href="{{ path('connect_github_start') }}">Login with Github</a>;
};

export default GitHubButton;
